<?php

namespace Bakgul\LaravelQueryHelper\Filters;

use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Bakgul\LaravelQueryHelper\Helpers\Time;
use Illuminate\Database\Eloquent\Builder;

class Last extends Filter
{
    protected function filter(Builder $query, mixed $filter): Builder
    {
        return $query->where('created_at', '>=', Time::from($filter[0]));
    }
}
