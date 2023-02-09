<?php

namespace Bakgul\LaravelQueryHelper\Tests\TestConcerns;

trait HasDebugger
{
    protected function clearDebugger()
    {
        if (class_exists(\Spatie\LaravelRay\Ray::class)) {
            ray()->clearAll();
        }

        if (config('debug-server.clear_all')) {
            $dump = resource_path('views/dump');

            copy("{$dump}.stub", "{$dump}.html");
        }
    }
}
