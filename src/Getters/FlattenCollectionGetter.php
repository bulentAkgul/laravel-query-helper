<?php

namespace Bakgul\LaravelQueryHelper\Getters;

use Bakgul\LaravelQueryHelper\Contracts\CollectionGetter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FlattenCollectionGetter extends CollectionGetter
{
    const LIMIT = 1;

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function last(): Collection
    {
        return $this->query()->limit(Arr::get($this->settings, 'limit.0', self::LIMIT))->get();
    }
}
