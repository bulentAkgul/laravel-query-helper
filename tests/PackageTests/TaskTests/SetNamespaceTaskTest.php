<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\TaskTests;

use Bakgul\LaravelHelpers\Helpers\Folder;
use Bakgul\LaravelHelpers\Helpers\Package;
use Bakgul\LaravelQueryHelper\Tasks\SetNamespace;
use Bakgul\LaravelQueryHelper\Tests\TestCase;
use Illuminate\Support\Facades\File;

class SetNamespaceTaskTest extends TestCase
{
    /** @test */
    public function it_will_convert_path_to_namespace()
    {
        Folder::add(
            base_path('packages/core/books/src/Models'),
            'Book.php',
            'namespace DummyNamespace;'
        );

        $this->assertEquals(
            'DummyNamespace\Book',
            SetNamespace::_(Package::path('books'), 'Book')
        );

        $this->assertEquals(
            'App\Models\User',
            SetNamespace::_(base_path(), 'User')
        );

        File::deleteDirectory(base_path('packages'));
    }
}
