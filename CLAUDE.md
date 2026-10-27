# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

`ndate22/ndate` — a PHP (^8.0) Composer library converting dates between AD (Gregorian) and BS (Bikram Sambat, Nepali). Depends on `nesbot/carbon` (^2.71 || ^3.8). Namespace `Ndate22\` → `src/` (PSR-4).

## Commands

```bash
composer install                       # no vendor/ is committed
./vendor/bin/pest                      # run all tests (what CI runs)
./vendor/bin/pest --filter="Invalid Date"   # run a single test by name
./vendor/bin/pest tests/Unit/NdateTest.php  # run one file
./vendor/bin/pint                      # format (Laravel Pint, default preset)
./vendor/bin/phpstan analyse src       # static analysis (no phpstan.neon; pass paths/level explicitly)
```

CI (`.github/workflows/php.yml`) runs `composer validate --strict`, then Pest, on pushes/PRs to `master`.

## Architecture

- `Ndate` is the public facade: `Ndate::to_bs($ad, $format)` returns an `AdToBs`; `Ndate::to_ad($bs, $format)` returns a `BsToAd`. Both convert eagerly in the constructor and are used via `__toString`.
- **All conversion is driven by the lookup table `Constants\Year::BS`**: one row per BS year starting at 1970 (row index = `year - 1970`), column 0 is the year, columns 1–12 are days in each BS month. The epoch is BS 1970-01-01 = AD 1913-04-13. Supported range is BS 1970–2100 / AD up to `Year::AD_END_DATE`. Extending the range means adding rows here and updating the `*_END_*` constants.
- `AdToBs` counts days from the AD epoch, then walks the `Year::BS` table day by day, tracking weekday (`number_of_day`, 1 = Sunday) alongside.
- `BsToAd` sums BS month lengths from the table, then walks Gregorian months forward from the AD epoch. It returns a Carbon (`finalDate`) and formats with Carbon's `format()` — it does not use the custom formatter and ignores `$lang`.
- BS formatting (`AdToBs` only): `Contacts\ToDateStringInterface::FORMATTER` maps format letters (`d D j l m M n Y`) to method names, and `Traits\ToDateStringTrait::toStringFormat()` dispatches to them via `strtr`. To add a format token, add it to `FORMATTER` and implement the method (and update the README table).
- Nepali output: `lang(Ndate::NP)` switches digits to Devanagari and month/weekday names to `Constants\Month::NP` / `Constants\Weekday::NP*`.

## Gotchas

- `AdToBs::format()` mutates the stored format **and** returns a plain string, so `->format(...)->lang(...)` does not work; call `lang()` first. `lang()` also mutates and returns `$this`. Tests rely on this mutation.
- Out-of-range input throws `Exceptions\InvalidDateException`.
- The directory is spelled `Contacts` (not `Contracts`); keep it — it's part of the public namespace.
