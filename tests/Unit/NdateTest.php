<?php

use Ndate22\Exceptions\InvalidDateException;
use Ndate22\Ndate;

test('AD date convert to BS Date', function () {
    $ad = '2023-11-08';
    $bs = '2080-07-22';
    $bs_np = '२०८०-०७-२२';

    $date_to_bs = Ndate::to_bs($ad);

    expect("{$date_to_bs}")->toBe($bs)
        ->and("{$date_to_bs->lang(Ndate::NP)}")->toBe($bs_np);
});

test('BS date convert to AD Date', function () {
    $ad = '2023-11-08';
    $bs = '2080-07-22';

    $date_to_ad = Ndate::to_ad($bs);

    expect("{$date_to_ad}")->toBe($ad);
});

test('Invalid Date', function () {
    $ad = '2044-11-08';
    $bs = '2101-11-08';

    expect(fn () => Ndate::to_bs($ad))->toThrow(InvalidDateException::class)
        ->and(fn () => Ndate::to_ad($bs))->toThrow(InvalidDateException::class);
});

test('BS day beyond month length is invalid', function () {
    // Kartik 2080 has 30 days
    expect((string) Ndate::to_ad('2080-07-30'))->toBe('2023-11-16')
        ->and(fn () => Ndate::to_ad('2080-07-31'))->toThrow(InvalidDateException::class)
        ->and(fn () => Ndate::to_ad('2080-07'))->toThrow(InvalidDateException::class);
});

test('Asar 2081 date issue', function () {
    $ad = '2024-07-03';
    $bs = '2081-03-19';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('July 1937 date issue', function () {
    $ad = '1937-07-12';
    $bs = '1994-03-29';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Date One', function () {
    $ad = '1983-01-02';
    $bs = '2039-09-18';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Date Two', function () {
    $ad = '2019-07-23';
    $bs = '2076-04-07';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Date Three', function () {
    $ad = '1950-07-24';
    $bs = '2007-04-09';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Date Four', function () {
    $ad = '1991-02-03';
    $bs = '2047-10-20';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Date Five', function () {
    $ad = '1987-11-08';
    $bs = '2044-07-22';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Date Six', function () {
    $ad = '1988-08-09';
    $bs = '2045-04-25';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Date Se7en', function () {
    $ad = '1935-11-17';
    $bs = '1992-08-02';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Leap year date', function () {
    $ad = '1996-02-29';
    $bs = '2052-11-17';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Leap year date 2', function () {
    $ad = '1994-04-08';
    $bs = '2050-12-26';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('Future date', function () {
    $ad = '2025-12-31';
    $bs = '2082-09-16';

    $to_bs = Ndate::to_bs($ad);
    $to_ad = Ndate::to_ad($bs);

    expect("{$to_bs}")->toBe($bs)
        ->and("{$to_ad}")->toBe($ad);
});

test('2083 corrected month lengths', function () {
    // Ashwin and Kartik 2083 have 31 days
    expect((string) Ndate::to_bs('2026-10-17'))->toBe('2083-06-31')
        ->and((string) Ndate::to_bs('2026-10-18'))->toBe('2083-07-01')
        ->and((string) Ndate::to_ad('2083-06-31'))->toBe('2026-10-17')
        ->and((string) Ndate::to_ad('2083-07-31'))->toBe('2026-11-17');
});

test('Days in BS month', function () {
    expect(Ndate::daysInMonth(2080, 7))->toBe(30)
        ->and(fn () => Ndate::daysInMonth(2101, 1))->toThrow(InvalidDateException::class)
        ->and(fn () => Ndate::daysInMonth(2080, 13))->toThrow(InvalidDateException::class);
});

test('Validate BS date', function () {
    expect(Ndate::isValidBs('2080-07-22'))->toBeTrue()
        ->and(Ndate::isValidBs('2080-07-31'))->toBeFalse()
        ->and(Ndate::isValidBs('2101-01-01'))->toBeFalse()
        ->and(Ndate::isValidBs('garbage'))->toBeFalse();
});

test('Integer getters', function () {
    $date = Ndate::to_bs('2023-11-08')->lang(Ndate::NP);

    expect($date->year())->toBe(2080)
        ->and($date->month())->toBe(7)
        ->and($date->day())->toBe(22)
        ->and($date->dayOfWeek())->toBe(3);
});

test('Fiscal year', function () {
    expect(Ndate::to_bs('2023-11-08')->fiscalYear())->toBe('2080/81')
        ->and(Ndate::to_bs('2023-04-20')->fiscalYear())->toBe('2079/80')
        ->and(Ndate::to_bs('2023-11-08')->lang(Ndate::NP)->fiscalYear())->toBe('२०८०/८१');
});

test('BS month calendar', function () {
    // Kartik 2080 starts on a Wednesday and has 30 days
    $weeks = Ndate::calendar(2080, 7);

    expect($weeks)->toHaveCount(5)
        ->and($weeks[0])->toBe([null, null, null, 1, 2, 3, 4])
        ->and($weeks[4])->toBe([26, 27, 28, 29, 30, null, null]);
});

test('Carbon interop', function () {
    $ad = new DateTimeImmutable('2023-11-08');

    expect((string) Ndate::to_bs($ad))->toBe('2080-07-22')
        ->and(Ndate::to_bs('2023-11-08')->toCarbon()->format('Y-m-d'))->toBe('2023-11-08')
        ->and(Ndate::to_ad('2080-07-22')->toCarbon()->format('Y-m-d'))->toBe('2023-11-08');
});

test('BS to AD in Nepali digits', function () {
    $date = Ndate::to_ad('2080-07-22', 'd/m/Y');

    expect((string) $date->lang(Ndate::NP))->toBe('०८/११/२०२३')
        ->and($date->format('Y-m-d'))->toBe('२०२३-११-०८');
});

test('Last supported AD date', function () {
    expect((string) Ndate::to_bs('2044-04-14'))->toBe('2100-12-30')
        ->and(fn () => Ndate::to_bs('2044-04-15'))->toThrow(InvalidDateException::class);
});

test('BS date arithmetic', function () {
    $bs = fn (string $date) => Ndate::to_bs(Ndate::to_ad($date)->toCarbon());

    $date = $bs('2080-07-22');

    expect((string) $date->addDays(10))->toBe('2080-08-02')
        ->and((string) $date->addDays(-22))->toBe('2080-06-30')
        ->and((string) $date)->toBe('2080-07-22')
        // Jestha 2080 has 32 days, Ashad 31: day is clamped
        ->and((string) $bs('2080-02-32')->addMonths(1))->toBe('2080-03-31')
        ->and((string) $bs('2080-12-15')->addMonths(1))->toBe('2081-01-15')
        ->and((string) $bs('2081-01-15')->addMonths(-1))->toBe('2080-12-15')
        ->and((string) $date->addYears(1))->toBe('2081-07-22')
        ->and($date->diffInDays($date->addDays(10)))->toBe(10)
        ->and($date->addDays(10)->diffInDays($date))->toBe(-10)
        ->and(fn () => $bs('2100-12-01')->addMonths(1))->toThrow(InvalidDateException::class);
});
