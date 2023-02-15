<?php

namespace Bakgul\LaravelQueryHelper\Tasks;

use Bakgul\LaravelHelpers\Helpers\Path;
use Bakgul\LaravelHelpers\Helpers\Str;

class SetNamespace
{
    public static function _(string $path, string $file): string
    {
        foreach (['src', 'app'] as $folder) {
            $model = Path::glue([$path, $folder, 'Models', "{$file}.php"]);

            if (file_exists($model)) return self::namespace($model);
        }

        return '';
    }

    private static function namespace(string $model): string
    {
        foreach (file($model) as $line) {
            if (!str_contains($line, 'namespace')) continue;

            return implode('\\', [
                trim(str_replace(['namespace', ';', ' '], '', $line)),
                str_replace('.php', '', Str::getTail($model))
            ]);
        }

        return '';
    }
}
