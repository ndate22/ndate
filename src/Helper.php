<?php

namespace Ndate22;

class Helper
{
    public static function addLeadingZeros($number, $width): string
    {
        return str_pad($number, $width, '0', STR_PAD_LEFT);
    }
}
