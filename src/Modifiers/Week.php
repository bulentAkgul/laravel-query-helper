<?php

namespace Bakgul\LaravelQueryHelper\Modifiers;

use Bakgul\LaravelQueryHelper\Contracts\Modify;
use Illuminate\Database\Eloquent\Builder;

class Week extends Modify
{
    public function modifyQuery(Builder $query, array $keys, string $column): Builder
    {
        return $query->when(
            in_array('week', $keys),
            fn ($q) => $q->addSelect($this->raw('week', $column))
        );
    }
}
