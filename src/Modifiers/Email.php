<?php

namespace Bakgul\LaravelQueryHelper\Modifiers;

use Bakgul\LaravelQueryHelper\Contracts\Modify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class Email extends Modify
{
    public function modifyQuery(Builder $query, array $keys, string $column): Builder
    {
        return $query->when(
            in_array('email', $keys),
            fn ($q) => $q->addSelect($this->email())
        );
    }

    private function email(): Expression
    {
        return DB::raw("SELECT SUBSTRING_INDEX(email,'@',-1) as email_provider");
    }
}
