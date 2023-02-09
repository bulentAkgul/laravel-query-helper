<?php

namespace Bakgul\LaravelQueryHelper\Actions;

use Bakgul\LaravelHelpers\Helpers\Arr;

class GetGroupKeys
{
    public static function _(array $request, string $model = ''): array
    {
        return Arr::get($request, 'group_keys')
            ?? (Arr::get($request, 'group') && $model ? $model::$groupKeys ?? [] : []);
    }
}
