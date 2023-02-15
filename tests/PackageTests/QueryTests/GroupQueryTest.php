<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\QueryTests;

use App\Models\User;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class GroupQueryTest extends TestCase
{
    /** @test */
    public function it_will_group_the_collection_by_the_given_keys_and_take_requested_items(): void
    {
        foreach (User::group(['email_provider']) as $provider => $users) {
            foreach ($users as $user) {
                $this->assertTrue(str_contains($user->email, "@{$provider}"));
            }
        }
    }

    /** @test */
    public function it_will_group_collection_based_on_modifiers_after_adding_them(): void
    {
        foreach (range(1, 50) as $_) {
            User::factory()->create([
                'created_at' => fake()->dateTimeBetween('-5 years')
            ]);
        }

        foreach (User::group(['year', 'month']) as $year => $users) {
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
            User::factory()->create([
                'updated_at' => fake()->dateTimeBetween('-5 years')
            ]);
        }

        foreach (User::group(['year', 'month'], column: 'updated_at') as $year => $users) {
            foreach ($users as $month => $users) {
                foreach ($users as $user) {
                    $this->assertEquals($year, $user->updated_at->year);
                    $this->assertEquals($month, $user->updated_at->month);
                }
            }
        }
    }

    /** @test */
    public function it_will_select_some_columns_and_number_of_records_as_requested(): void
    {
        foreach (range(1, 50) as $_) {
            User::factory()->create([
                'created_at' => fake()->dateTimeBetween('-5 years')
            ]);
        }

        foreach (User::group(['year', 'email_provider'], 3, true, ['name', 'email', 'created_at']) as $year => $users) {
            foreach ($users as $provider => $users) {
                $this->assertTrue($users->count() <= 3);

                foreach ($users as $i => $user) {
                    if ($i) $this->assertTrue($user->created_at <= $users[$i]->created_at);

                    $this->assertEquals(['name', 'email', 'created_at', 'email_provider', 'year'], array_keys($user->toArray()));
                    $this->assertEquals($year, $user->created_at->year);
                    $this->assertEquals($provider, $user->email_provider);
                    $this->assertTrue(str_contains($user->email, "@{$provider}"));
                }
            }
        }
    }
}
