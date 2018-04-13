<?php

namespace ScoutEngines\Elasticsearch;

use Laravel\Scout\EngineManager;
use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder as ElasticBuilder;
use Aws\ElasticsearchService\ElasticsearchPhpHandler;


class ElasticsearchProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        
    $handler = new ElasticsearchPhpHandler(config('scout.elasticsearch.aws_loc'));
        
        app(EngineManager::class)->extend('elasticsearch', function($app) {
            return new ElasticsearchEngine(ElasticBuilder::create()
                ->setHandler($handler)
                ->setHosts(config('scout.elasticsearch.hosts'))
                ->build(),
                config('scout.elasticsearch.index')
            );
        });
    }
}
