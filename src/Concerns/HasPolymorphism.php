<?php

namespace Bakgul\LaravelQueryHelper\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasPolymorphism
{
    public function scopeMorph(Builder $query, object $data, string $prefix)
    {
        return $query
            ->where("{$prefix}able_id", $data->id)
            ->where("{$prefix}able_type", get_class($data));
    }
}
