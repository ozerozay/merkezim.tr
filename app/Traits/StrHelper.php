<?php

namespace App\Traits;

use Transliterator;

trait StrHelper
{
    public static function strTitle($string)
    {
        return Transliterator::create('tr-Title')
            ->transliterate($string);
    }

    public static function strLower($string)
    {
        return Transliterator::create('tr-Lower')
            ->transliterate($string);
    }

    public static function strUpper($string)
    {
        return Transliterator::create('tr-Upper')
            ->transliterate($string);
    }
}
