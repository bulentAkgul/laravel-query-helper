<?php

namespace Bakgul\LaravelQueryHelper\Concerns;

use Bakgul\LaravelQueryHelper\Queries\ModificationQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait IsModifyable
{
    public static function scopeModify(
        Builder $query,
        array $keys = [],
        array $select = ['*'],
        string $column = 'created_at'
    ): Builder {
        return ModificationQuery::_($query, $keys, $select, $column);
    }
}
