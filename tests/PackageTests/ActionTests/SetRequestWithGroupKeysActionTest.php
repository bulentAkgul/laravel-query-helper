<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\ActionTests;

use App\Models\User;
use Bakgul\LaravelQueryHelper\Actions\SetRequestWithGroupKeys;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class SetRequestWithGroupKeysActionTest extends TestCase
{
    /** @test */
    public function it_will_append_group_keys_to_the_request_if_groupped_result_is_expected(): void
    {
        $this->assertEquals(
            ['group_keys' =>  []],
            SetRequestWithGroupKeys::_(User::class)
        );

        $this->changeRequest(['group' => true]);

        $this->assertEquals(
            ['group_keys' =>  ['email_provider'], 'group' => true],
            SetRequestWithGroupKeys::_(User::class)
        );
    }
}
