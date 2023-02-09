<?php

namespace Bakgul\LaravelQueryHelper\Tests\QueryTests\QueryTests;

use BookHub\Bridge\Users\Facades\Users;
use Tests\TestCase;

class SortQueryTest extends TestCase
{
    private $employees;

    public function setUp(): void
    {
        parent::setUp();

        $this->setFullApp(0);
    }

    /** @test */
    public function it_will_sort_by_given_columns_and_directions(): void
    {
        foreach (range(1, 50) as $_) {
            Users::model('User')::factory()->create([
                'name' => ['John', 'Jane', 'Michael', 'Bob', 'Stuart'][rand(0, 4)]
            ]);
        }

        $result = Users::query('User')->sort([['name'], ['email', 'desc']])->get();

        foreach ($result as $i => $user) {
            if ($i == 0) continue;

            $this->assertTrue($user->name >= $result[$i - 1]->name);

            if ($user->name == $result[$i - 1]->name) {
                $this->assertTrue($user->email <= $result[$i - 1]->email);
            }
        }
    }
}
