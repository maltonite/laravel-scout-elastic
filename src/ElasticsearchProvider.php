<?php

namespace ScoutEngines\Elasticsearch;

use Laravel\Scout\EngineManager;
use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder as ElasticBuilder;
use Aws\ElasticsearchService\ElasticsearchPhpHandler;
use Illuminate\Support\Facades\App;


class ElasticsearchProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $handler = '';
        if (App::environment('production', 'staging'))
        {
            $handler = new ElasticsearchPhpHandler(config('scout.elasticsearch.aws_loc'));
        }
        
        app(EngineManager::class)->extend('elasticsearch', function($app) use ($handler) {
            $eB = ElasticBuilder::create();
            if (App::environment('production', 'staging'))
            {
              $eB = $eB->setHandler($handler);
            }
            return new ElasticsearchEngine($eB
                ->setHosts(config('scout.elasticsearch.hosts'))
                ->build(),
                config('scout.elasticsearch.index')
            );
        });
    }
}
