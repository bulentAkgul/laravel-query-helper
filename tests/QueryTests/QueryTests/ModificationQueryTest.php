<?php

namespace Bakgul\LaravelQueryHelper\Tests\QueryTests\QueryTests;

use Bakgul\LaravelQueryHelper\Queries\ModificationQuery;
use BookHub\Bridge\Users\Facades\Users;
use Tests\TestCase;

class ModificationQueryTest extends TestCase
{
    private $employees;

    public function setUp(): void
    {
        parent::setUp();

        $this->setFullApp(0);
    }

    /** @test */
    public function it_will_add_modifications_to_the_query(): void
    {
        Users::model('User')::factory()->count(50)->create([
            'created_at' => fake()->dateTime($max = 'now')
        ]);

        $this->assertTrue(
            str_contains(
                ModificationQuery::_(Users::query('User'), ['year', 'week'])->toSql(),
                "select *, DATE_FORMAT(created_at, '%v') as week, DATE_FORMAT(created_at, '%Y') as year"
            )
        );
    }
}
