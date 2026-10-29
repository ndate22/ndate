<?php

declare(strict_types=1);

namespace Ndate22;

use Carbon\Carbon;
use Exception;
use Ndate22\Constants\Year;
use Ndate22\Contacts\ToDateStringInterface;
use Ndate22\Exceptions\InvalidDateException;
use Ndate22\Traits\ToDateStringTrait;

class AdToBs implements ToDateStringInterface
{
    use ToDateStringTrait;

    private int $year;

    private int $month;

    private int $day;

    private int $number_of_day;

    private string $lang;

    private Carbon $adDate;

    /**
     * @throws InvalidDateException
     */
    public function __construct($date = null, ?string $format = null, string $lang = 'en')
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();

        if (! $this->isValidDate($date)) {
            throw new InvalidDateException('Invalid date: '.$date);
        }

        if ($format) {
            $this->format = $format;
        }

        $this->lang = $lang;
        $this->adDate = $date;

        $this->convert($date->format('Y-m-d'));
    }

    /**
     * Ad date convert to bs date
     *
     * @throws Exception
     */
    public function convert($date): void
    {
        $days = $this->getTotalAdDays($date);

        $this->number_of_day = $days % 7 + Year::NUMBER_OF_DAY;

        // ponytail: assumes the epoch is the 1st of month 1 (Year::BS_MONTH/BS_DAY)
        foreach (Year::BS as $row) {
            for ($month = 1; $month <= 12; $month++) {
                if ($days < $row[$month]) {
                    [$this->year, $this->month, $this->day] = [$row[0], $month, $days + 1];

                    return;
                }

                $days -= $row[$month];
            }
        }
    }

    /**
     * Calculate total days to convert
     *
     * @throws Exception
     */
    private function getTotalAdDays($date): int
    {
        $start_date = new \DateTimeImmutable(Year::AD_START_DATE);
        $end_date = new \DateTimeImmutable($date);

        return (int) $end_date->diff($start_date)->format('%a');
    }

    public function lang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function format(string $format): string
    {
        $this->format = $format;

        return $this->toStringFormat();
    }

    public function getDay(): int|string
    {
        return $this->lang == 'np' ? $this->toNepaliNumber($this->day) : $this->day;
    }

    public function getMonth(): int|string
    {
        return $this->lang == 'np' ? $this->toNepaliNumber($this->month) : $this->month;
    }

    public function getYear(): int|string
    {
        return $this->lang == 'np' ? $this->toNepaliNumber($this->year) : $this->year;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function month(): int
    {
        return $this->month;
    }

    public function day(): int
    {
        return $this->day;
    }

    /**
     * Day of the week, 0 (Sunday) to 6 (Saturday)
     */
    public function dayOfWeek(): int
    {
        return $this->number_of_day - 1;
    }

    /**
     * Nepal fiscal year (starts Shrawan 1), e.g. 2080/81
     */
    public function fiscalYear(): string
    {
        $start = $this->month >= 4 ? $this->year : $this->year - 1;

        return $this->localize($start.'/'.Helper::addLeadingZeros(($start + 1) % 100, 2));
    }

    /**
     * The AD date this was converted from
     */
    public function toCarbon(): Carbon
    {
        return $this->adDate->copy();
    }

    /**
     * New date moved by the given days (negative goes back)
     *
     * @throws InvalidDateException
     */
    public function addDays(int $days): self
    {
        return new self($this->adDate->copy()->addDays($days), $this->format, $this->lang);
    }

    /**
     * New date moved by BS months; the day is clamped to the target month's length
     *
     * @throws InvalidDateException
     */
    public function addMonths(int $months): self
    {
        $total = $this->year * 12 + $this->month - 1 + $months;
        [$year, $month] = [intdiv($total, 12), $total % 12 + 1];
        $day = min($this->day, Ndate::daysInMonth($year, $month));

        $ad = Ndate::to_ad(sprintf('%d-%02d-%02d', $year, $month, $day))->finalDate;

        return new self($ad, $this->format, $this->lang);
    }

    /**
     * @throws InvalidDateException
     */
    public function addYears(int $years): self
    {
        return $this->addMonths($years * 12);
    }

    /**
     * Days from this date to the other (negative when the other is earlier)
     */
    public function diffInDays(self $other): int
    {
        return (int) $this->adDate->copy()->startOfDay()
            ->diff($other->adDate->copy()->startOfDay())
            ->format('%r%a');
    }

    public function __toString()
    {
        return $this->toStringFormat();
    }

    private function toNepaliNumber($number): string
    {
        $nepaliNumbers = [
            '0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४',
            '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९',
        ];

        return str_replace(array_keys($nepaliNumbers), array_values($nepaliNumbers), (string) $number);
    }

    /**
     * Check given date is valid or not
     */
    private function isValidDate(Carbon $date): bool
    {
        return $date->between(Year::AD_START_DATE, Year::AD_END_DATE);
    }
}
