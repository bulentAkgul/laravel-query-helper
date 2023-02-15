<?php

namespace Bakgul\LaravelQueryHelper\Concerns;

use Bakgul\LaravelQueryHelper\Queries\FilterQuery;
use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

trait IsFilterable
{
    public static function scopeFilter(Builder $query, array $filters): Builder
    {
        $query->filters = $filters;

        return FilterQuery::_($query, self::collectFilters());
    }

    public static function collectFilters(?array $map = null): Repository|array
    {
        $cacheKey = 'filters_' . get_class();

        $filters = Cache::get($cacheKey);

        if ($filters) return $filters;

        $filters = self::setFilters($map);

        Cache::forever($cacheKey, $filters);

        return $filters;
    }

    public static function setFilters(?array $map = null): array
    {
        return [
            'self' => self::$filters['self'] ?? [],
            'with' => self::setRelationsFilters($map)
        ];
    }

    public static function setRelationsFilters(?array $map): array
    {
        return array_map(
            fn ($x) => self::getRelationsFilters(get_class(), $x, $map),
            self::$filters['with'] ?? []
        );
    }

    public static function getRelationsFilters(string $from, string $to, ?array $map): array
    {
        $newMap = self::addSelfToFrom($from, $to, $map);

        return in_array($to, $map[$from] ?? []) ? [] : $to::setFilters($newMap);
    }

    public static function addSelfToFrom(string $from, string $to, ?array $map): array
    {
        if (is_null($map)) return [];

        $map[$from] = [...($map[$from] ?? []), $to];

        return $map;
    }
}
