<?php

namespace Bakgul\LaravelQueryHelper\Contracts;

use Bakgul\LaravelQueryHelper\Actions\SetRawDateExpression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

abstract class Modify
{
    protected array $formatters;

    public function __construct()
    {
        $this->formatters = config('query-helper.formatters');
    }

    protected function raw(string $key, string $column = 'created_at'): Expression
    {
        return SetRawDateExpression::_($key, $this->formatters[$key], $column);
    }

    abstract public function modifyQuery(Builder $query, array $keys, string $column): Builder;
}
