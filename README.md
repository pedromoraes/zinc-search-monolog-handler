# Zinc Search Monolog Handler


Searching for logs for your applications can be tedious and challenging. ZincSearch solves the problem very elegantly. You can use standard log forwarders like fluentd, fluent-bit, vector, syslog-ng or others to forward logs to ZincSearch. ZincSearch can then store the indexed logs in S3 or on disk and provide fast search for your logs.
## ZincSearch  Monolog Handler is used for pushing laravel log into the ZincSearch for collection and analysis.

Log forwarders can read the log files incrementally as new logs appear in them and can then forward them in batches in order to be more efficient in sending them
## Requirements

## Install ZincSearch using [docker-compose.yml](https://github.com/tasmidur/zinc-search-monolog-handler/blob/master/docker-compose.yml)

or you can install by following [this documentation](https://docs.zincsearch.com/installation/)

## Install

Install [zinc-search-monolog-handler](https://packagist.org/packages/tasmidur/zinc-search-monolog-handler).

```shell
composer require tasmidur/kafka-monolog-handler
```

## Get Started

1.Modify `config/logging.php`.
### Without Kafka SASL Config
```php
return [
    'channels' => [
        // ...
        "zincSearch" => \Tasmidur\ZincSearchMonologHandler\ZincSearchLogger::getInstance(
            indexName: env('ZINC_SEARCH_INDEX', "zinc_log"),
            baseUrl: env('ZINC_SEARCH_BASE_URL', 'http://admin:admin123@localhost:4080/api')
        ),
    ],
];
```
### ZincSearch with SSL_VERIFY
```php
return [
    'channels' => [
        // ...
       "zincSearch" => \Tasmidur\ZincSearchMonologHandler\ZincSearchLogger::getInstance(
            indexName: env('LOG_INDEX', "zinc_log"),
            baseUrl: env('ZINC_SEARCH_BASE_URL', 'http://admin:admin123@localhost:4080/api'),
            options: [
                "is_ssl_verify" => true //true or false
            ]
        ),
    ],
];
```
2.Modify `.env`.
```
LOG_CHANNEL=zincSearch
ZINC_SEARCH_INDEX=zinc_log
ZINC_SEARCH_BASE_URL=url

```
## The system Log view in ZincSearch
![ZincSearch](https://github.com/tasmidur/zinc-search-monolog-handler/blob/master/zinc-search-dashboard.png)
## License

[MIT](LICENSE)
