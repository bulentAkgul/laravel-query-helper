<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\ActionTests;

use App\Models\Ability;
use App\Models\User;
use Bakgul\LaravelQueryHelper\Actions\GetGroupKeys;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class GetGroupKeysActionTest extends TestCase
{
    /** @test */
    public function it_will_return_the_group_keys_if_necessary(): void
    {
        $this->assertEquals(['name'], GetGroupKeys::_(['group_keys' => ['name']]));

        $this->assertEquals([], GetGroupKeys::_(['group' => true], Ability::class));

        $this->assertEquals(['email_provider'], GetGroupKeys::_(['group' => true], User::class));
    }
}
