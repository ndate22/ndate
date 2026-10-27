<?php

namespace Ndate22;

use Exception;

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
}
