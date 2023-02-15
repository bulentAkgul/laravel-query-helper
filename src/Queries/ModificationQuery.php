<?php

namespace Bakgul\LaravelQueryHelper\Queries;

use Illuminate\Database\Eloquent\Builder;

class ModificationQuery
{
    public static function _(Builder $query, array $keys, array $select = ['*'], string $column = 'created_at'): Builder
    {
        $query = $query->select($select);

        foreach (config('query-helper.modifiers') as $modifier) {
            $query = (new $modifier)->modifyQuery($query, $keys, $column);
        }

        return $query;
    }
}
