<?php

namespace Bakgul\LaravelQueryHelper\Filters;

use Bakgul\LaravelQueryHelper\Actions\SetRawDateExpression;
use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Year extends Filter
{
    protected function filter(Builder $query, mixed $filter): Builder
    {
        return $this->filters($query, $filter, $this->callback());
    }

    private function callback(): callable
    {
        return fn ($query, $year) => $query->orWhere(fn ($q) => $q->addSelect(SetRawDateExpression::_('year')), $year);
    }
}
