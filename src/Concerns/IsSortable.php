<?php

namespace Bakgul\LaravelQueryHelper\Concerns;

use Bakgul\LaravelQueryHelper\Queries\SortQuery;
use Illuminate\Database\Eloquent\Builder;

trait IsSortable
{
    public static function scopeSort(Builder $query, array $orders): Builder
    {
        return SortQuery::_($query, $orders);
    }
}
