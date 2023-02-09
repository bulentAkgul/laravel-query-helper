<?php

namespace Bakgul\LaravelQueryHelper\Queries;

use App\Helpers\Config;
use Illuminate\Database\Eloquent\Builder;

class ModificationQuery
{
    public static function _(Builder $query, array $keys, array $select = ['*'], string $column = 'created_at'): Builder
    {
        $query = $query->select($select);

        foreach (Config::book('query.modifiers') as $modifier) {
            $query = (new $modifier)->modifyQuery($query, $keys, $column);
        }

        return $query;
    }
}
