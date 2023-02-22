<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\TaskTests;

use Bakgul\LaravelHelpers\Helpers\File;
use Bakgul\LaravelQueryHelper\Tasks\GetPackageThatHasModel;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class GetPackageThatHasModelTaskTest extends TestCase
{
    /** @test */
    public function it_will_return_the_package_path_and_file_name_when_a_model_that_belongst_to_that_package_is_searched()
    {
        $root = base_path('packages/core');
        $package = 'dummies';
        $model = 'DummyModel';

        File::create("{$root}/{$package}/src/Models", "{$model}.php");

        $this->assertEquals(
            ["{$root}/{$package}", $model],
            GetPackageThatHasModel::_($model)
        );

        File::deleteDirectory(base_path('packages'));
    }

    /** @test */
    public function it_will_return_the_package_in_app_namespace_when_model_is_in_app()
    {
        $root = base_path();
        $model = 'DummyModel';

        File::create("{$root}/app/Models", "{$model}.php");

        $this->assertEquals(
            [$root, $model],
            GetPackageThatHasModel::_($model)
        );

        unlink("{$root}/app/Models/{$model}.php");
    }
}
