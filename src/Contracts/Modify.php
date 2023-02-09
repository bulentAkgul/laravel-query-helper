<?php

namespace Bakgul\LaravelQueryHelper\Contracts;

use App\Helpers\Config;
use Bakgul\LaravelQueryHelper\Actions\SetRawDateExpression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

abstract class Modify
{
    protected array $formatters;

    public function __construct()
    {
        $this->formatters = Config::book('query.formatters');
    }

    protected function raw(string $key, string $column = 'created_at'): Expression
    {
        return SetRawDateExpression::_($key, $this->formatters[$key], $column);
    }

    abstract public function modifyQuery(Builder $query, array $keys, string $column): Builder;
}
