<?php

namespace Bakgul\LaravelQueryHelper\Filters;

use Bakgul\LaravelQueryHelper\Actions\SetRawDateExpression;
use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Day extends Filter
{
    protected function filter(Builder $query, mixed $filter): Builder
    {
        return $this->filters($query, $filter, $this->callback());
    }

    private function callback(): callable
    {
        return fn ($query, $day) => $query->orWhere(
            fn ($q) => $q->addSelect(SetRawDateExpression::_('day')),
            ucfirst($day)
        );
    }
}
