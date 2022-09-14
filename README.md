# Zinc Search Monolog Handler


ZincSearch  Monolog Handler is used for pushing laravel log into the ZincSearch for collection and analysis.Zinc is a search engine that does full text indexing. It is a lightweight alternative to Elasticsearch and runs using a fraction of the resources. It uses bluge as the underlying indexing library.

It is very simple and easy to operate as opposed to Elasticsearch which requires a couple dozen knobs to understand and tune which you can get up and running in 2 minutes

It is a drop-in replacement for Elasticsearch if you are just ingesting data using APIs and searching using kibana (Kibana is not supported with zinc. Zinc provides its own UI).

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
