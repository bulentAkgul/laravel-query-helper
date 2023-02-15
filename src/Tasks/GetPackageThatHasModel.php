<?php

namespace Bakgul\LaravelQueryHelper\Tasks;

use Bakgul\LaravelHelpers\Helpers\Arr;
use Bakgul\LaravelHelpers\Helpers\Folder;
use Bakgul\LaravelHelpers\Helpers\Package;
use Bakgul\LaravelHelpers\Helpers\Str;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GetPackageThatHasModel
{
    public static function _(string $file): array
    {
        dump(self::containers());
        foreach (self::containers() as $container) {
            $files = self::files("{$container}/Models", $file);

            if (!$files) continue;

            return [
                Str::dropTail($container),
                str_replace('.php', '', Arr::first($files))
            ];
        }

        throw new FileNotFoundException(
            'The model named ' . Str::studly($file) . ' does not exist.'
        );
    }

    private static function containers(): array
    {
        return [
            ...array_map(
                fn ($x) => "{$x}/src",
                Package::list()
            ), base_path('app'), base_path('src')
        ];
    }

    public static function files(string $path, string $file): array
    {
        return array_intersect(
            Folder::content($path),
            array_map(
                fn ($x) => Str::studly($x) . '.php',
                self::alternatives($path, $file)
            )
        );
    }

    private static function alternatives(string $path, string $file): array
    {
        $plural = Str::plural($file);
        $singular = Str::singular($file);
        $package = Str::getTail(Str::singular($path));

        $alternatives = [];

        foreach ([$file, $singular, $plural] as $name) {
            $alternatives[] = $name;
            $alternatives[] = "{$package}_{$file}";
        }

        return $alternatives;
    }
}
