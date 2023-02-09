<?php

namespace Bakgul\LaravelQueryHelper\Tests\QueryTests\TaskTests;

use Bakgul\LaravelHelpers\Helpers\Folder;
use Bakgul\LaravelQueryHelper\Tasks\GetPackageThatHasModel;
use Bakgul\LaravelQueryHelper\Tests\TestCase;
use Illuminate\Support\Facades\File;

class GetPackageThatHasModelTaskTest extends TestCase
{
    /** @test */
    public function it_will_return_the_package_path_and_file_name_when_a_model_that_belongst_to_that_package_is_searched()
    {
        $root = base_path('packages/core');
        $package = 'dummies';
        $model = 'DummyModel';

        Folder::add("{$root}/{$package}/src/Models", "{$model}.php");

        $this->assertEquals(
            ["{$root}/{$package}", "{$model}.php"],
            GetPackageThatHasModel::_($model)
        );

        File::deleteDirectory(base_path('packages/core'));
    }

    /** @test */
    public function it_will_return_the_package_in_app_namespace_when_model_is_in_app()
    {
        $root = base_path();
        $model = 'DummyModel';

        Folder::add("{$root}/app/Models", "{$model}.php");

        $this->assertEquals(
            ["{$root}", "{$model}.php"],
            GetPackageThatHasModel::_($model)
        );

        unlink("{$root}/app/Models/{$model}.php");
    }
}
