<?php

namespace Bakgul\LaravelQueryHelper\Tests;

use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    protected function changeRequest(array $data): void
    {
        request()->merge($data);
    }
}
