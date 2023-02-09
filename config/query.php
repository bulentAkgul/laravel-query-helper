<?php

return [
    'seeders' => [],
    'modifiers' => [
        \Bakgul\LaravelQueryHelper\Modifiers\Day::class,
        \Bakgul\LaravelQueryHelper\Modifiers\Email::class,
        \Bakgul\LaravelQueryHelper\Modifiers\Month::class,
        \Bakgul\LaravelQueryHelper\Modifiers\Week::class,
        \Bakgul\LaravelQueryHelper\Modifiers\Year::class,
    ],
    'columns' => [
        'email' => 'email_provider',
    ],
    'formatters' => [
        'day' => '%W',
        'week' => '%v',
        'month' => '%m',
        'year' => '%Y',
    ],
];
