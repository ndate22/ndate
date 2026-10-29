# Ndate (Nepali date converter)

A PHP library for `AD to BS` and `BS to AD`

## Install

Run the following command from you terminal:

`composer require ndate22/ndate`

## Usage

### AD to BS

```php
use Ndate22\Ndate;

# Example 01

$date_to_bs = Ndate::to_bs("2023-11-08");

echo $date_to_bs // OUTPUT: 2080-07-22 
echo $date_to_bs->lang(Ndate::NP) // OUTPUT: २०८०-०७-२२


# Example 02

$date_to_bs = Ndate::to_bs("2023-11-08", 'd/m/Y');

echo $date_to_bs // OUTPUT: 22/07/2080
echo $date_to_bs->format('l, d M, Y') // Wednesday, 22 Kartik, 2080
echo $date_to_bs->lang(Ndate::NP)->format('l, d M, Y') // OUTPUT: बुधवार, २२ कार्तिक, २०८०
```


---
#### Available Date Format

| Format | Description                                              | Example             |
|--------|----------------------------------------------------------|---------------------|
| `d`    | Day of the month, 2 digits with leading zeros            | `01` to `32`        |
| `D`    | A textual representation of a day, three letters         | `Mon` for Monday    |
| `j`    | Day of the month without leading zeros                   | `1` to `31`         |
| `l`    | A full textual representation of the day of the week     | `Monday`, `Tuesday` |
| `N`    | ISO numeric day of the week                              | `1` (Mon) to `7` (Sun) |
| `w`    | Numeric day of the week                                  | `0` (Sun) to `6` (Sat) |
| `m`    | Numeric representation of a month, with leading zeros    | `01` to `12`        |
| `M`    | A full textual representation of a month                 | `Baishak`, `Jestha` |
| `n`    | Numeric representation of a month, without leading zeros | `1` to `12`         |
| `t`    | Number of days in the month                              | `29` to `32`        |
| `Y`    | A full numeric representation of a year, 4 digits        | `2078`              |
| `y`    | A two digit representation of a year                     | `78`                |

#### Date parts

```php
$date = Ndate::to_bs("2023-11-08");

$date->year();      // 2080
$date->month();     // 7
$date->day();       // 22
$date->dayOfWeek(); // 3 (0 = Sunday)
```

### BS to AD

```php
use Ndate22\Ndate;

# Example 01

$date_to_ad = Ndate::to_ad("2080-07-22");

echo $date_to_ad // OUTPUT: 2023-11-08

# Example 02
$date_to_ad = Ndate::to_ad("2080-07-22", 'd/m/Y');

echo $date_to_ad // OUTPUT: 08/11/2023
```

### Helpers

```php
use Ndate22\Ndate;

Ndate::daysInMonth(2080, 7);    // 30
Ndate::isValidBs('2080-07-22'); // true
Ndate::isValidBs('2080-07-31'); // false (Kartik 2080 has 30 days)
```

