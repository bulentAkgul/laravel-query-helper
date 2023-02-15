<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\QueryTests;

use App\Models\User;
use Bakgul\LaravelQueryHelper\Queries\ModificationQuery;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class ModificationQueryTest extends TestCase
{
    /** @test */
    public function it_will_add_modifications_to_the_query(): void
    {
        User::factory()->count(50)->create([
            'created_at' => fake()->dateTime($max = 'now')
        ]);

        $this->assertTrue(
            str_contains(
                ModificationQuery::_(User::query(), ['year', 'week'])->toSql(),
                "select *, DATE_FORMAT(created_at, '%v') as week, DATE_FORMAT(created_at, '%Y') as year"
            )
        );
    }
}
