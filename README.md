# Kafka Monolog Handler

ZincSearch  Monolog Handler is used for pushing laravel log into the ZincSearch for collection and analysis

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

## License

[MIT](LICENSE)
