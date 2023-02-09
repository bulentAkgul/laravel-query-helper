<?php

namespace Bakgul\LaravelQueryHelper\Tests;

use Bakgul\LaravelDumpServer\Concerns\HasDumper;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use HasDumper, LazilyRefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->resetDumper();
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
