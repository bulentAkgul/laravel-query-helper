<?php

namespace Bakgul\LaravelQueryHelper\Contracts;

use Bakgul\LaravelQueryHelper\Tasks\SerializeParameters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

abstract class CollectionGetter
{
    protected array $filters;
    protected array $settings;

    public function __construct(protected string $model, array|string $parameters)
    {
        [$this->filters, $this->settings] = SerializeParameters::_($parameters);
    }

    protected function query(): Builder
    {
        return $this->model::query()
            ->modify(Arr::get($this->settings, 'group_keys', []))
            ->filter($this->filters)
            ->orderBy('id', 'desc');
    }

    abstract public function all(): Collection;

    abstract public function last(): Collection;
}
