<?php

namespace Ndate22;

use Exception;
use Ndate22\Constants\Year;
use Ndate22\Exceptions\InvalidDateException;

class Ndate
{
    const BS = 1;

    const AD = 2;

    const EN = 'en';

    const NP = 'np';

    /**
     * @throws Exception
     */
    public static function to_bs(?string $date = null, ?string $format = null, string $lang = 'en')
    {
        return new AdToBs($date, $format, $lang);
    }

    public static function to_ad(?string $date = null, string $format = 'Y-m-d', string $lang = 'en')
    {
        return new BsToAd($date, $format, $lang);
    }

    /**
     * Number of days in a BS month
     *
     * @throws InvalidDateException
     */
    public static function daysInMonth(int $year, int $month): int
    {
        if ($year < Year::BS_YEAR || $year > Year::BS_END_YEAR || $month < 1 || $month > 12) {
            throw new InvalidDateException("Invalid BS month: $year-$month");
        }

        return Year::BS[$year - Year::BS_YEAR][$month];
    }

    /**
     * Check a BS date (Y-m-d) exists and is in the supported range
     */
    public static function isValidBs(string $date): bool
    {
        try {
            new BsToAd($date);

            return true;
        } catch (InvalidDateException) {
            return false;
        }
    }
}
