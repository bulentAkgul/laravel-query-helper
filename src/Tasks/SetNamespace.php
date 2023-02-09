<?php

namespace Bakgul\LaravelQueryHelper\Tasks;

use Bakgul\LaravelHelpers\Helpers\Path;

class SetNamespace
{
    public static function _(string $path, string $file): string
    {
        foreach (['src', 'app'] as $folder) {
            $model = Path::glue([$path, $folder, $file]);

            if (file_exists($model)) return self::namespace($model);
        }

        return '';
    }

    private static function namespace(string $model): string
    {
        foreach (file($model) as $line) {
            if (!str_contains($line, 'namespace')) continue;

            return Path::glue([
                trim(str_replace(['namespace', ';', ' '], '', $line)),
                str_replace('.php', '', $model)
            ], '\\');
        }

        return '';
    }
}
