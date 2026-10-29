# Ndate (Nepali date converter)

A PHP library for converting dates between AD (Gregorian) and BS (Bikram Sambat), with Nepali (Devanagari) output, fiscal years and month calendars.

- AD → BS and BS → AD conversion
- English or Nepali output (`२०८०-०७-२२`, `बुधवार, २२ कार्तिक`)
- PHP `date()`-style format tokens for BS dates
- Nepal fiscal year, BS month calendar grid, date validation
- Works with Carbon and any `DateTimeInterface`

## Requirements

- PHP 8.0+
- [nesbot/carbon](https://carbon.nesbot.com) ^2.71 or ^3.8 (installed automatically)

## Install

```bash
composer require ndate22/ndate
```

## Supported range

| Conversion          | From         | To           |
|---------------------|--------------|--------------|
| AD → BS (`to_bs`)   | `1913-04-13` | `2043-04-14` |
| BS → AD (`to_ad`)   | `1970-01-01` | `2100-12-30` |

Dates outside this range throw `Ndate22\Exceptions\InvalidDateException`.

## AD to BS

```php
use Ndate22\Ndate;

$date = Ndate::to_bs('2023-11-08');

echo $date;                   // 2080-07-22
echo $date->lang(Ndate::NP);  // २०८०-०७-२२
```

With a format:

```php
$date = Ndate::to_bs('2023-11-08', 'd/m/Y');

echo $date;                                              // 22/07/2080
echo $date->format('l, d M, Y');                         // Wednesday, 22 Kartik, 2080
echo $date->lang(Ndate::NP)->format('l, d M, Y');        // बुधवार, २२ कार्तिक, २०८०
```

Input can be any string Carbon can parse, a `DateTime`/`DateTimeImmutable`/`Carbon` instance, or `null` for today:

```php
Ndate::to_bs(new DateTime('2023-11-08'));
Ndate::to_bs(Carbon\Carbon::now());
Ndate::to_bs(); // today
```

> **Note:** `format()` returns a string, so call `lang()` before it: `$date->lang(Ndate::NP)->format(...)`.
> Both `lang()` and `format()` change the object, so later `echo $date` uses the new language and format.

### Format tokens

| Token | Description                                  | Example                   |
|-------|----------------------------------------------|---------------------------|
| `d`   | Day of the month, 2 digits with leading zero | `01` to `32`              |
| `j`   | Day of the month without leading zero        | `1` to `32`               |
| `D`   | Short weekday name                           | `Wed` / `बुध`              |
| `l`   | Full weekday name                            | `Wednesday` / `बुधवार`      |
| `N`   | ISO day of the week                          | `1` (Mon) to `7` (Sun)    |
| `w`   | Day of the week                              | `0` (Sun) to `6` (Sat)    |
| `m`   | Month, 2 digits with leading zero            | `01` to `12`              |
| `n`   | Month without leading zero                   | `1` to `12`               |
| `M`   | Month name                                   | `Kartik` / `कार्तिक`        |
| `t`   | Number of days in the month                  | `29` to `32`              |
| `Y`   | Year, 4 digits                               | `2080`                    |
| `y`   | Year, 2 digits                               | `80`                      |

Any other character is printed as is. Format letters are always replaced, so avoid them in literal text (`'Today: Y'` also replaces the `t` and `y`).

### Date parts

```php
$date = Ndate::to_bs('2023-11-08');

$date->year();       // 2080
$date->month();      // 7
$date->day();        // 22
$date->dayOfWeek();  // 3 (0 = Sunday)
$date->fiscalYear(); // 2080/81
$date->toCarbon();   // Carbon instance of the original AD date
```

The getters always return integers, even after `lang(Ndate::NP)`. `fiscalYear()` follows the language: `२०८०/८१`.

Nepal's fiscal year starts on Shrawan 1 (month 4), so `2080-03-31` is in `2079/80` and `2080-04-01` is in `2080/81`.

## BS to AD

```php
use Ndate22\Ndate;

$date = Ndate::to_ad('2080-07-22');

echo $date;                        // 2023-11-08
echo $date->format('d/m/Y');       // 08/11/2023
echo $date->lang(Ndate::NP);       // ०८/११/२०२३
$date->toCarbon();                 // Carbon instance
```

The BS input must be `Y-m-d`. The output format uses [PHP date format](https://www.php.net/manual/en/datetime.format.php) tokens (it is formatted by Carbon). `lang(Ndate::NP)` converts the digits only; AD month and weekday names stay in English.

## Helpers

```php
use Ndate22\Ndate;

Ndate::daysInMonth(2080, 7);    // 30
Ndate::isValidBs('2080-07-22'); // true
Ndate::isValidBs('2080-07-31'); // false (Kartik 2080 has 30 days)
Ndate::isValidBs('2101-01-01'); // false (out of range)
```

### Month calendar

`Ndate::calendar($year, $month)` returns the BS month as weeks starting on Sunday. Each week has 7 slots: the day number, or `null` for days outside the month.

```php
Ndate::calendar(2080, 7);
// [
//     [null, null, null,    1,    2,    3,    4],
//     [   5,    6,    7,    8,    9,   10,   11],
//     [  12,   13,   14,   15,   16,   17,   18],
//     [  19,   20,   21,   22,   23,   24,   25],
//     [  26,   27,   28,   29,   30, null, null],
// ]
```

`daysInMonth()` and `calendar()` throw `InvalidDateException` for a year outside 1970–2100 or a month outside 1–12.

## Error handling

```php
use Ndate22\Exceptions\InvalidDateException;

try {
    Ndate::to_ad('2080-07-31');
} catch (InvalidDateException $e) {
    // invalid or out-of-range date
}
```

## Testing

```bash
composer install
./vendor/bin/pest
```

## License

MIT
