<?php

namespace Bakgul\LaravelQueryHelper\Actions;

use App\Helpers\Config;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class SetRawDateExpression
{
    public static function _(string $key, string $formatter = '', string $column = 'created_at'): Expression
    {
        $formatter = $formatter ?: config("query_helper.formatters.{$key}");

        return DB::raw("DATE_FORMAT({$column}, '{$formatter}') as {$key}");
    }
}
