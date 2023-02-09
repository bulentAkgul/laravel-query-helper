<?php

namespace Bakgul\LaravelQueryHelper\Concerns;

use Bakgul\LaravelQueryHelper\Queries\FilterQuery;
use Bakgul\LaravelQueryHelper\Queries\SortQuery;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Concerns\SortsQuery;

trait IsSortable
{
    public static function scopeSort(Builder $query, array $orders): Builder
    {
        return SortQuery::_($query, $orders);
    }
}
