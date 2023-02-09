<?php

namespace Bakgul\LaravelQueryHelper\Tests\QueryTests\QueryTests;

use Bakgul\LaravelHelpers\Helpers\Arr;
use App\Helpers\Str;
use BookHub\Bridge\Users\Facades\Users;
use BookHub\Pool\Abilities\Models\Ability;
use Tests\TestCase;

class GroupQueryTest extends TestCase
{
    private $employees;

    public function setUp(): void
    {
        parent::setUp();

        $this->setFullApp(0);
    }

    /** @test */
    public function it_will_group_the_collection_by_either_the_given_or_default_keys_and_take_requested_items(): void
    {
        Arr::map(
            Arr::crossJoin(Ability::get(), range(0, 2)),
            fn ($x) => Ability::create([...$x[0]->toArray(), 'id' => Str::orderedUuid()])
        );

        foreach (Ability::group() as $domain => $abilities) {
            $this->assertEquals([$domain], $abilities->pluck('domain')->unique()->toArray());
        }

        foreach (Ability::group(take: 5) as $abilities) {
            $this->assertLessThanOrEqual(5, $abilities->count());
        }

        foreach (Ability::group(['domain', 'action'], 2) as $domain => $abilities) {
            foreach ($abilities as $action => $abilities) {
                $this->assertLessThanOrEqual(2, $abilities->count());

                foreach ($abilities as $ability) {
                    $this->assertEquals(
                        [$domain, $action],
                        [$ability->domain, $ability->action]
                    );
                }
            }
        }
    }

    /** @test */
    public function it_will_group_collection_based_on_modifiers_after_adding_them(): void
    {
        foreach (range(1, 50) as $_) {
            Users::model('User')::factory()->create([
                'created_at' => fake()->dateTimeBetween('-5 years')
            ]);
        }

        foreach (Users::query('User')->group(['year', 'month']) as $year => $users) {
            foreach ($users as $month => $users) {
                foreach ($users as $user) {
                    $this->assertEquals($year, $user->created_at->year);
                    $this->assertEquals($month, $user->created_at->month);
                }
            }
        }
    }

    /** @test */
    public function it_will_group_collection_based_on_updated_at_column_after_modified_(): void
    {
        foreach (range(1, 50) as $_) {
            Users::model('User')::factory()->create([
                'updated_at' => fake()->dateTimeBetween('-5 years')
            ]);
        }

        foreach (Users::query('User')->group(['year', 'month'], column: 'updated_at') as $year => $users) {
            foreach ($users as $month => $users) {
                foreach ($users as $user) {
                    $this->assertEquals($year, $user->updated_at->year);
                    $this->assertEquals($month, $user->updated_at->month);
                }
            }
        }
    }
}
