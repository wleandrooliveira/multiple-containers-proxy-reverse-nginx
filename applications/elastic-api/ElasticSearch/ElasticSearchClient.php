<?php

namespace App\ElasticSearch;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchClient
{
    public static function getClient()
    {
        $client = ClientBuilder::create()
            ->setHosts([env('ELASTICSEARCH_HOST') . ':' . env('ELASTICSEARCH_PORT')])
            ->build();
        return $client;
    }
}
