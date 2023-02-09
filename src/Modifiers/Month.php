<?php

namespace Bakgul\LaravelQueryHelper\Modifiers;

use Bakgul\LaravelQueryHelper\Contracts\Modify;
use Illuminate\Database\Eloquent\Builder;

class Month extends Modify
{
    public function modifyQuery(Builder $query, array $keys, string $column): Builder
    {
        return $query->when(
            in_array('month', $keys),
            fn ($q) => $q->addSelect($this->raw('month', $column))
        );
    }
}
