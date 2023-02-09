<?php

namespace Bakgul\LaravelQueryHelper\Actions;

class SetRequestWithGroupKeys
{
    public static function _(string $model): array
    {
        return array_merge(
            $r = request()->all(),
            ['group_keys' => GetGroupKeys::_($r, $model)]
        );
    }
}
