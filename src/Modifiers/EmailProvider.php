<?php

namespace Bakgul\LaravelQueryHelper\Modifiers;

use Bakgul\LaravelQueryHelper\Contracts\Modify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class EmailProvider extends Modify
{
    public function modifyQuery(Builder $query, array $keys, string $column): Builder
    {
        $raw = $this->rawQuery();

        return $query->when(
            in_array('email_provider', $keys),
            fn ($q) => $q->addSelect($raw)
        );
    }

    private function rawQuery(): Expression
    {
        return DB::raw("SUBSTRING_INDEX(email, '@', -1) as email_provider");
    }
}
