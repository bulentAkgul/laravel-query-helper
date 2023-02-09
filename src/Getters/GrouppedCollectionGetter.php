<?php

namespace Bakgul\LaravelQueryHelper\Getters;

use Bakgul\LaravelQueryHelper\Contracts\CollectionGetter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GrouppedCollectionGetter extends CollectionGetter
{
    const LIMIT = 1;
    private $collection;
    private $flattenCollection;

    public function all(): Collection
    {
        return $this->query()->get()->groupBy($this->keys());
    }

    public function last(): Collection
    {
        $this->collection = $this->all();

        $this->dropExtraInstances();

        return $this->collection;
    }

    private function keys(): array
    {
        $keys = [];
        $keyMap = $this->keyMap();

        foreach ($this->settings['group_keys'] as $key) {
            $keys[$key] = Arr::get($keyMap, $key, $key);
        }

        return $keys;
    }

    private function keyMap(): array
    {
        $keyMap = [
            'trader' => 'trader_id',
            'market' => 'tradeable_type',
        ];

        if ($this->groupByMorph()) {
            $prefix = Str::snake(Str::getTail($this->model, '\\'));
            $keyMap['morph_id'] = "{$prefix}able_id";
            $keyMap['morph_type'] = "{$prefix}able_type";
        }

        return $keyMap;
    }

    private function groupByMorph(): bool
    {
        return (bool) array_intersect($this->settings['group_keys'], ['morph_id', 'morph_type']);
    }

    private function dropExtraInstances(): void
    {
        $this->flattenCollection($this->collection);

        foreach ($this->flattenCollection as $keys => $instances) {
            data_set($this->collection, $keys, collect($instances)->take($this->settings['limit'][0]));
        }
    }

    private function flattenCollection(Collection $collection, string $keys = ''): void
    {
        foreach ($collection as $key => $collectionOrInstance) {
            if ($collectionOrInstance instanceof $this->model) {
                $this->flattenCollection[trim($keys, '.')][] = $collectionOrInstance;
            } else {
                $this->flattenCollection($collectionOrInstance, "{$keys}.{$key}");
            }
        }
    }
}
