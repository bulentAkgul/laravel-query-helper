<?php

namespace Bakgul\LaravelQueryHelper\Mixins;

use Bakgul\LaravelQueryHelper\Queries\GroupQuery;

class CollectionMixin
{
    public function group()
    {
        return function (array $keys, int $take = 0) {
            return GroupQuery::_($this->groupBy($keys), $take);
        };
    }
}
