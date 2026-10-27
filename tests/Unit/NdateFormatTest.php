<?php

use Ndate22\Ndate;

test('AD date convert to BS Date with format', function () {
    $ad = '2023-11-08';
    $bs = '22/07/2080';
    $bs_np = '२२/०७/२०८०';

    $date = Ndate::to_bs($ad, 'd/m/Y');

    expect("$date")->toBe($bs)
        ->and("{$date->format('d/m/Y')}")->toBe($bs)
        ->and("{$date->lang(Ndate::NP)}")->toBe($bs_np);
});

test('AD date convert to BS Date with days and month format', function () {
    $ad = '2023-11-08';
    $bs = 'Wed, 22 Kartik, 2080';

    $date = Ndate::to_bs($ad, 'D, d M, Y');
    $date_to_bs_1 = Ndate::to_bs($ad);

    expect("$date")->toBe($bs)
        ->and("{$date->format('l, d M, Y')}")->toBe('Wednesday, 22 Kartik, 2080')
        ->and("{$date->lang(Ndate::NP)}")->toBe('बुधवार, २२ कार्तिक, २०८०');
});
