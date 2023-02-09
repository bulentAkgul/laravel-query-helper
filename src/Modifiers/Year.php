<?php

namespace Bakgul\LaravelQueryHelper\Modifiers;

use Bakgul\LaravelQueryHelper\Contracts\Modify;
use Illuminate\Database\Eloquent\Builder;

class Year extends Modify
{
    public function modifyQuery(Builder $query, array $keys, string $column): Builder
    {
        return $query->when(
            in_array('year', $keys),
            fn ($q) => $q->addSelect($this->raw('year', $column))
        );
    }
}
