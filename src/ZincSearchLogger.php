<?php

namespace Tasmidur\ZincSearchMonologHandler;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class ZincSearchLogger
{
    /**
     * Get the logging definition of zincSearch Log channel
     * @param string $indexName
     * @param string $baseUrl
     * @param array $options
     * @return array
     */
    public static function getInstance(
        string $indexName,
        string $baseUrl,
        string $username,
        string $password,
        array $options = [],
    ): array {
        $default = [
            'driver' => 'custom',
            'via' => static::class,
            'index' => $indexName,
            'base_url' => $baseUrl,
            'username' => $username,
            'password' => $password,
            'fallback' => 'stdout'
        ];
        return array_merge($default, $options);
    }

    /**
     * @throws Throwable
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('zincSearch');

        throw_if(
            empty($config['base_url']),
            new \Exception('Provided baseUrl is invalid', ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
        );
        $indexName = $config['index'];
        $baseUrl = $config['base_url'];
        $username = $config['username'];
        $password = $config['password'];
        $handler = new ZincSearchLogHandler(
            index: $indexName,
            baseUrl: $baseUrl,
            config: $config,
            username: $username,
            password: $password
        );
        $logger->pushHandler($handler);
        return $logger;
    }
}
