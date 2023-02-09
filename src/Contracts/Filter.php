<?php

namespace Bakgul\LaravelQueryHelper\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Filter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $filter = $this->filterBy();

        $builder = $next($query);

        return Arr::has($query->filters, $filter)
            ? $this->applyFilter($builder, $query->filters[$filter])
            : $builder;
    }

    private function filterBy(): string
    {
        return Str::snake(class_basename($this));
    }

    private function applyFilter(Builder $query, mixed $filter): Builder
    {
        return property_exists($query, 'relation')
            ? $this->relation($query, $filter)
            : $this->filter($query, $filter);
    }

    private function relation(Builder $query, mixed $filter)
    {
        return $query->whereHas(
            $query->relation,
            fn ($q) => $this->filter($q, $filter)
        );
    }

    protected function filters(Builder $query, mixed $filter, callable $callback): Builder
    {
        return $query->where(function ($query) use ($filter, $callback) {
            foreach ((array) $filter as $term) {
                $callback($query, $term);
            }
        });
    }

    abstract protected function filter(Builder $query, mixed $filter): Builder;
}
