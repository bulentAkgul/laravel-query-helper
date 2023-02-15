<?php

namespace Bakgul\LaravelQueryHelper\Filters;

use Bakgul\LaravelHelpers\Helpers\Str;
use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Text extends Filter
{
    public $column = '';

    protected function filter(Builder $query, mixed $filter): Builder
    {
        return $this->filters($query, $filter, $this->callback());
    }

    protected function callback(): callable
    {
        return fn ($query, $term) => $this->filterQuery($query, $term);
    }

    protected function filterQuery(Builder $query, string $term): Builder
    {
        return $query->orWhere($this->column(), 'LIKE', str_replace('***', '%', $term));
    }

    protected function column(): string
    {
        return $this->column ?: Str::snake(Str::getTail(get_class($this), '\\'));
    }
}
