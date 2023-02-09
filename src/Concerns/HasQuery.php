<?php

namespace Bakgul\LaravelQueryHelper\Concerns;

trait HasQuery
{
    use HasPolymorphism, IsFilterable, IsGrouppable, IsSortable;
}
