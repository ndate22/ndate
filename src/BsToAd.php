<?php

declare(strict_types=1);

namespace Ndate22;

use Carbon\Carbon;
use Ndate22\Constants\Year;
use Ndate22\Exceptions\InvalidDateException;

class BsToAd
{
    public Carbon $finalDate;

    /**
     * @throws InvalidDateException
     */
    public function __construct(
        public string $date,
        public string $format = 'Y-m-d',
        public string $lang = 'en'
    ) {
        $this->validateDate();
        $this->convert();
    }

    /**
     * @throws InvalidDateException
     */
    private function validateDate(): void
    {
        $parts = explode('-', $this->date);

        if (count($parts) !== 3) {
            throw new InvalidDateException('Invalid date');
        }

        [$year, $month, $day] = array_map('intval', $parts);

        if ($year < Year::BS_YEAR || $year > Year::BS_END_YEAR) {
            throw new InvalidDateException('Invalid date');
        }

        if ($month < 1 || $month > 12) {
            throw new InvalidDateException('Invalid date');
        }

        if ($day < 1 || $day > Year::BS[$year - Year::BS_YEAR][$month]) {
            throw new InvalidDateException('Invalid date');
        }
    }

    private function convert(): void
    {
        [$yy, $mm, $dd] = array_map('intval', explode('-', $this->date));

        $index = $yy - Year::BS_YEAR;
        $days = $dd - 1;

        foreach (array_slice(Year::BS, 0, $index) as $row) {
            $days += array_sum(array_slice($row, 1));
        }

        $days += array_sum(array_slice(Year::BS[$index], 1, $mm - 1));

        $this->finalDate = Carbon::parse(Year::AD_START_DATE)->addDays($days);
    }

    public function isLeapYear(int $year): bool
    {
        return ($year % 400 == 0) || (($year % 100 != 0) && ($year % 4 == 0));
    }

    public function __toString(): string
    {
        return $this->finalDate->format($this->format);
    }
}
