<?php

namespace Ndate22\Contacts;

interface ToDateStringInterface
{
    const FORMATTER = [
        'd' => 'day_with_leading_zero',
        'D' => 'short_day_name',
        'j' => 'getDay',
        'l' => 'full_day_name',

        'm' => 'month_with_leading_zero',
        'M' => 'month_name',
        'n' => 'getMonth',

        'Y' => 'getYear',
    ];

    public function day_with_leading_zero(): string;

    public function short_day_name(): string;

    public function full_day_name(): string;

    public function month_with_leading_zero(): string;

    public function month_name(): string;
}
