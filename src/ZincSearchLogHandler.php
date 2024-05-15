<?php

namespace Tasmidur\ZincSearchMonologHandler;

use Illuminate\Support\Facades\Http;
use Monolog\Formatter\ElasticsearchFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

/**
 * class KafkaLogHandler
 */
class ZincSearchLogHandler extends AbstractProcessingHandler
{
    /**
     * @var array
     */
    protected array $config;
    /**
     * @var string|mixed
     */
    private string $fallback;

    /**
     * @var string
     */
    private string $baseUrl;
    /**
     * @var string
     */

    private string $username;
    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $index;


    /**
     * @param string $index
     * @param string $baseUrl
     * @param string $username
     * @param string $password
     * @param array $config
     * @param string $fallback
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(
        string $index,
        string $baseUrl,
        string $username,
        string $password,
        array $config,
        string $fallback = 'stdout',
        int $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->config = $config;
        $this->fallback = $fallback;
        $this->index = $index;
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        $formatter = new ElasticsearchFormatter($this->index, "_doc");
        $record = $formatter->format($record);
        /** ES index and type override avoid the collision*/
        if (array_key_exists("_index", $record)) {
            unset($record['_index']);
        }
        if (array_key_exists("_type", $record)) {
            unset($record['_type']);
        }

        try {
            $this->baseUrl = str_ends_with($this->baseUrl, '/') ? substr($this->baseUrl, 0, -1) : $this->baseUrl;
            $this->baseUrl .= "/" . $this->index . "/_doc";

            Http::withOptions([ 'verify' => $this->config['is_ssl_verify'] ?? false ])
                ->withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl, $record)
                ->throw(function (\Illuminate\Http\Client\Response $httpResponse, $httpException) {
                    app('log')->channel($this->fallback)
                        ->debug(get_class($httpResponse) . ' - ' . get_class($httpException));
                    app('log')->channel($this->fallback)
                        ->debug(
                            'Http/Curl call error. Destination:: ' .
                            $this->baseUrl .
                            ' and Response:: ' .
                            $httpResponse->body()
                        );
                });
        } catch (\Throwable $e) {
            app('log')->channel('errorlog')->info('here error'.$e->__toString(), $e->getTrace());
            $method = strtolower($record['level_name']);
            app('log')->channel($this->fallback)
                ->$method($e->getMessage(), $record);
        }
    }
}
