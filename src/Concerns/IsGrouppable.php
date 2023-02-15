<?php

namespace Bakgul\LaravelQueryHelper\Concerns;

use Bakgul\LaravelQueryHelper\Queries\ModificationQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait IsGrouppable
{
    use IsModifyable;

    public static function scopeGroup(
        Builder $query,
        array $keys = [],
        int $take = 0,
        bool $isLast = false,
        array $select = ['*'],
        string $column = 'created_at'
    ): Collection {
        $keys = $keys ?: (self::$groupKeys ?? $keys);

        return ($isLast ? $query->latest() : $query)
            ->modify($keys, $select, $column)
            ->get()
            ->group($keys, $take);
    }
}
