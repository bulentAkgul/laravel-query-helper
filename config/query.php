<?php

return [
    'modifiers' => [
        \Bakgul\LaravelQueryHelper\Modifiers\Day::class,
        \Bakgul\LaravelQueryHelper\Modifiers\EmailProvider::class,
        \Bakgul\LaravelQueryHelper\Modifiers\Month::class,
        \Bakgul\LaravelQueryHelper\Modifiers\Week::class,
        \Bakgul\LaravelQueryHelper\Modifiers\Year::class,
    ],
    'formatters' => [
        'day' => '%W',
        'week' => '%v',
        'month' => '%m',
        'year' => '%Y',
    ],
];
