<?php

namespace Bakgul\LaravelQueryHelper\Helpers;

use Illuminate\Database\Eloquent\Model;

class Time
{
    public static function last(string $period): array
    {
        return [self::from($period), date('Y-m-d')];
    }

    public static function from(string $period): string
    {
        return date('Y-m-d', strtotime("-{$period}"));
    }

    public static function to(string $period): string
    {
        return date('Y-m-d', strtotime("+{$period}"));
    }

    public static function year(Model $instance, string $column = 'created_at'): string
    {
        return $instance->$column->year;
    }

    public static function month(Model $instance, string $column = 'created_at'): string
    {
        return substr("0{$instance->$column->month}", -2);
    }

    public static function week(Model $instance, string $column = 'created_at'): string
    {
        return substr("0{$instance->$column->isoWeek()}", -2);
    }

    public static function day(Model $instance, string $column = 'created_at'): string
    {
        return $instance->$column->dayName;
    }
}
