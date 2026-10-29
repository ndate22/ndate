<?php

namespace Ndate22\Contacts;

interface ToDateStringInterface
{
    const FORMATTER = [
        'd' => 'day_with_leading_zero',
        'D' => 'short_day_name',
        'j' => 'getDay',
        'l' => 'full_day_name',
        'N' => 'iso_day_of_week',
        'w' => 'day_of_week',

        'm' => 'month_with_leading_zero',
        'M' => 'month_name',
        'n' => 'getMonth',
        't' => 'days_in_month',

        'Y' => 'getYear',
        'y' => 'short_year',
    ];

    public function day_with_leading_zero(): string;

    public function short_day_name(): string;

    public function full_day_name(): string;

    public function month_with_leading_zero(): string;

    public function month_name(): string;

    public function iso_day_of_week(): string;

    public function day_of_week(): string;

    public function days_in_month(): string;

    public function short_year(): string;
}
