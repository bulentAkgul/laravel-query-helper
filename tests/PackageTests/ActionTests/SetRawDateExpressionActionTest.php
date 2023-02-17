<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\ActionTests;

use Bakgul\LaravelQueryHelper\Actions\SetRawDateExpression;
use Bakgul\LaravelQueryHelper\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SetRawDateExpressionActionTest extends TestCase
{
    /** @test */
    public function it_will_return_raw_date_expression(): void
    {
        $this->assertEquals(
            "DATE_FORMAT(created_at, '%W') as day",
            SetRawDateExpression::_('day')->getValue(DB::connection()->getQueryGrammar())
        );

        $this->assertEquals(
            "DATE_FORMAT(updated_at, '%W') as day",
            SetRawDateExpression::_('day', column: 'updated_at')->getValue(DB::connection()->getQueryGrammar())
        );

        $this->assertEquals(
            "DATE_FORMAT(created_at, '%w') as week",
            SetRawDateExpression::_('week', '%w')->getValue(DB::connection()->getQueryGrammar())
        );

        $this->assertEquals(
            "DATE_FORMAT(created_at, '%m') as month",
            SetRawDateExpression::_('month')->getValue(DB::connection()->getQueryGrammar())
        );

        $this->assertEquals(
            "DATE_FORMAT(created_at, '%Y') as year",
            SetRawDateExpression::_('year')->getValue(DB::connection()->getQueryGrammar())
        );
    }
}
