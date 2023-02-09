<?php

namespace Bakgul\LaravelQueryHelper\Tests\QueryTests\TaskTests;

use App\Helpers\Package;
use Bakgul\LaravelQueryHelper\Tasks\SetNamespace;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class SetNamespaceTaskTest extends TestCase
{
    /** @test */
    public function it_will_convert_path_to_namespace()
    {
        $this->assertEquals(
            'BookHub\Tenant\Books\Models\Book',
            SetNamespace::_(Package::path('books'), name: 'Book')
        );

        $this->assertEquals(
            'BookHub\Tenant\Books\Models\Book',
            SetNamespace::_(Package::path('books'))
        );

        $this->assertEquals(
            'BookHub\Landlord\BridgeLandlord\Models\User',
            SetNamespace::_(Package::path('bridge-landlord'), name: 'User')
        );
    }
}
