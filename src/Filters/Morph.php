<?php

namespace Core\Query\Filters;

use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Bakgul\LaravelQueryHelper\Tasks\GetPackageThatHasModel;
use Bakgul\LaravelQueryHelper\Tasks\SetNamespace;
use Illuminate\Database\Eloquent\Builder;

class Morph extends Filter
{
    protected function filter(Builder $query, mixed $filter): Builder
    {
        [$package, $file] = GetPackageThatHasModel::_($filter[1]);

        $model = SetNamespace::_($package, $file);

        return $query
            ->where("{$filter[0]}able_type", $model)
            ->whereIn("{$filter[0]}able_id", array_slice($filter, 2));
    }
}
