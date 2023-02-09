<?php

namespace Bakgul\LaravelQueryHelper;

use Bakgul\LaravelQueryHelper\Mixins\CollectionMixin;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Collection::mixin(new CollectionMixin);

        $this->publishes([
            __DIR__ . '/../config/query.php' => config_path('query-helper.php'),
        ], 'query-helper');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/query.php', 'query_helper');
    }
}
