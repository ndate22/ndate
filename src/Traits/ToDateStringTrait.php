<?php

namespace Ndate22\Traits;

use Ndate22\Constants\Month;
use Ndate22\Constants\Weekday;
use Ndate22\Helper;
use Ndate22\Ndate;

/**
 * @property int $day
 * @property int $month
 * @property int $number_of_day
 * @property string $lang
 */
trait ToDateStringTrait
{
    private string $format = 'Y-m-d';

    /**
     * Date format to string
     */
    protected function toStringFormat(): string
    {
        $replace = [];
        foreach (self::FORMATTER as $key => $method) {
            if (str_contains($this->format, $key)) {
                $replace[$key] = (string) $this->$method();
            }
        }

        return strtr($this->format, $replace);
    }

    /**
     * Day of the month, 2 digits with leading zeros
     */
    public function day_with_leading_zero(): string
    {
        $day = Helper::addLeadingZeros($this->day, 2);

        return $this->lang == Ndate::NP
            ? $this->toNepaliNumber($day)
            : $day;
    }

    /**
     * A short name of a day of the week
     */
    public function short_day_name(): string
    {
        return $this->lang == Ndate::NP
            ? Weekday::NP_SHORT[$this->number_of_day - 1]
            : Weekday::EN_SHORT[$this->number_of_day - 1];
    }

    /**
     * A full name of the day of the week
     */
    public function full_day_name(): string
    {
        return $this->lang == Ndate::NP
            ? Weekday::NP[$this->number_of_day - 1]
            : Weekday::EN[$this->number_of_day - 1];
    }

    public function month_with_leading_zero(): string
    {
        $month = Helper::addLeadingZeros($this->month, 2);

        return $this->lang == Ndate::NP
            ? $this->toNepaliNumber($month)
            : $month;
    }

    public function month_name(): string
    {
        return $this->lang == Ndate::NP
            ? Month::NP[$this->month - 1]
            : Month::NP_EN[$this->month - 1];
    }
}
