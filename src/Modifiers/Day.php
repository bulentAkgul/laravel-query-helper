<?php

namespace Bakgul\LaravelQueryHelper\Modifiers;

use Bakgul\LaravelQueryHelper\Contracts\Modify;
use Illuminate\Database\Eloquent\Builder;

class Day extends Modify
{
    public function modifyQuery(Builder $query, array $keys, string $column): Builder
    {
        return $query->when(
            in_array('day', $keys),
            fn ($q) => $q->addSelect($this->raw('day', $column))
        );
    }
}
