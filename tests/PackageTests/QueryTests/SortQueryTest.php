<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\QueryTests;

use App\Models\User;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class SortQueryTest extends TestCase
{
    /** @test */
    public function it_will_sort_by_given_columns_and_directions(): void
    {
        foreach (range(1, 50) as $_) {
            User::factory()->create([
                'name' => ['John', 'Jane', 'Michael', 'Bob', 'Stuart'][rand(0, 4)]
            ]);
        }

        $result = User::sort([['name'], ['email', 'desc']])->get();

        foreach ($result as $i => $user) {
            if ($i == 0) continue;

            $this->assertTrue($user->name >= $result[$i - 1]->name);

            if ($user->name == $result[$i - 1]->name) {
                $this->assertTrue($user->email <= $result[$i - 1]->email);
            }
        }
    }
}
