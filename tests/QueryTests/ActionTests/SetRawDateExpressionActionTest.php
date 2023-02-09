<?php

namespace Bakgul\LaravelQueryHelper\Tests\QueryTests\ActionTests;

use Bakgul\LaravelQueryHelper\Actions\SetRawDateExpression;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class SetRawDateExpressionActionTest extends TestCase
{
    /** @test */
    public function it_will_return_raw_date_expression(): void
    {
        $this->assertEquals(
            "DATE_FORMAT(created_at, '%W') as day",
            SetRawDateExpression::_('day')->getValue()
        );

        $this->assertEquals(
            "DATE_FORMAT(updated_at, '%W') as day",
            SetRawDateExpression::_('day', column: 'updated_at')->getValue()
        );

        $this->assertEquals(
            "DATE_FORMAT(created_at, '%w') as week",
            SetRawDateExpression::_('week', '%w')->getValue()
        );

        $this->assertEquals(
            "DATE_FORMAT(created_at, '%m') as month",
            SetRawDateExpression::_('month')->getValue()
        );

        $this->assertEquals(
            "DATE_FORMAT(created_at, '%Y') as year",
            SetRawDateExpression::_('year')->getValue()
        );
    }
}
