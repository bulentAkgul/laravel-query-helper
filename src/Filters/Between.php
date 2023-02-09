<?php

namespace Bakgul\LaravelQueryHelper\Filters;

use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Between extends Filter
{
    protected function filter(Builder $query, mixed $filter): Builder
    {
        return $query->whereBetween('created_at', $filter);
    }
}
