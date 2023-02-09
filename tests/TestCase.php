<?php

namespace Bakgul\LaravelQueryHelper\Tests;

use Bakgul\LaravelQueryHelper\Tests\TestConcerns\HasDebugger;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, HasDebugger, LazilyRefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->clearDebugger();
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
