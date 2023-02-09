<?php

namespace Bakgul\LaravelQueryHelper\Queries;

use Illuminate\Support\Collection;

class GroupQuery
{
    public static function _(Collection $collection, int $take = 0): Collection
    {
        return $collection->map(function ($items) use ($take) {
            if ($items->first() instanceof Collection) {
                return self::_($items, $take);
            }

            return $take ? $items->take($take) : $items;
        });
    }
}
