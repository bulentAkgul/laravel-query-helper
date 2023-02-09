<?php

namespace Bakgul\LaravelQueryHelper\Queries;

use Illuminate\Database\Eloquent\Builder;

class SortQuery
{
    public static function _(Builder $query, array $orders): Builder
    {
        foreach ($orders as $orderBy) {
            $query->orderBy($orderBy[0], $orderBy[1] ?? 'asc');
        }

        return $query;
    }
}
