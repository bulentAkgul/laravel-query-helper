<?php

namespace Bakgul\LaravelQueryHelper\Filters;

use App\Helpers\Str;
use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Bakgul\LaravelQueryHelper\Tasks\GetPackageThatHasModel;
use Bakgul\LaravelQueryHelper\Tasks\SetNamespace;
use Illuminate\Database\Eloquent\Builder;


class MorphMany extends Filter
{
    /*
     | Filter for many-to-many polymorphic relationship.
     |
     | The array of filter should be ['method', 'relation', 'column', ...ids]
     |
     |  method:   by: when the model has morphedByMany
     |            to: when the model has morphToMany
     |  relation: The name of the relationship method.
     |            This will be used to find the target class. So we assume
     |            that the related model name and the method name are the same
     |            core like "users" as method name and "User" as model name.
     |  column:   Let's say this is 'user'. When the method is 'by' it will becom
     |            'userable_type' and 'userable_id'. When the method is 'to' it
     |            will become 'user_id'
     | ...ids     The list of ids, but not in another array.
     |
     */

    protected function filter(Builder $query, mixed $filter): Builder
    {
        return $query->whereHas(Str::plural($filter[1]), $this->{$filter[0]}($filter));
    }

    protected function by(array $filter): callable
    {
        [$package, $file] = GetPackageThatHasModel::_($filter[1]);

        $model = SetNamespace::_($package, $file);

        return function ($query) use ($filter, $model) {
            return $query
                ->where("{$filter[2]}able_type", $model)
                ->whereIn("{$filter[2]}able_id", array_slice($filter, 3));
        };
    }

    protected function to(array $filter): callable
    {
        return function ($query) use ($filter) {
            return $query->whereIn("{$filter[2]}_id", array_slice($filter, 3));
        };
    }
}
