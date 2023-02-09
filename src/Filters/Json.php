<?php

namespace Bakgul\LaravelQueryHelper\Filters;

use App\Helpers\Str;
use Bakgul\LaravelQueryHelper\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Json extends Filter
{
    protected $column;

    protected function filter(Builder $query, mixed $filter): Builder
    {
        return $query->where(function ($query) use ($filter) {
            foreach ($filter as $key => $value) {
                if (is_string($value)) {
                    $query->where("{$this->column()}->{$key}", 'LIKE', str_replace('***', '%', $value));
                } else {
                    $query->whereJsonContains("{$this->column}->$key", $value);
                }
            }
        });
    }

    private function column(): string
    {
        return $this->column ?: Str::snake(Str::getTail(get_class($this), '\\'));
    }
}
