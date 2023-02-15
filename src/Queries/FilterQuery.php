<?php

namespace Bakgul\LaravelQueryHelper\Queries;

use Bakgul\LaravelHelpers\Helpers\Arr;
use Bakgul\LaravelHelpers\Helpers\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class FilterQuery
{
    public static function _(Builder $query, $pipeline): Builder
    {
        foreach ($pipeline as $key => $filterClasses) {
            $query = match ($key) {
                'with' => self::with($query, $filterClasses),
                default => self::self($query, $pipeline['self'])
            };
        }

        return $query;
    }

    public static function with(Builder $query, array $pipeline): Builder
    {
        foreach ($pipeline as $relation => $relationalFilters) {
            if (Arr::hasNot($query->filters['with'] ?? [], $relation)) continue;

            $relationalQuery = $query;

            $relationalQuery->filters = $query->filters['with'][$relation];
            $relationalQuery->relation = self::relation($query, $relation);

            $query = self::_($relationalQuery, $relationalFilters);
        }

        return $query;
    }

    private static function relation(Builder $query, string $relation): string
    {
        return Str::prepend($relation, property_exists($query, 'relation') ? $query->relation : '', '.');
    }

    private static function self(Builder $query, array $pipeline): Builder
    {
        $pipeline = self::senitizePipeline($pipeline, $query->filters);

        return $pipeline ? self::filter($query, $pipeline) : $query;
    }

    private static function senitizePipeline(array $pipeline, array $filters): array
    {
        $models = self::setFilterableModels($filters);

        return array_filter($pipeline, fn ($x) => in_array(Str::getTail($x, '\\'), $models));
    }

    private static function setFilterableModels(array $filters): array
    {
        return array_map(fn ($x) => Str::studly($x), array_keys(Arr::delete($filters, 'with')));
    }

    private static function filter(Builder $query, array $pipeline): Builder
    {
        return app(Pipeline::class)->send($query)->through($pipeline)->thenReturn();
    }
}
