<?php

namespace App\Enums;

class BannerDisplayType
{
    const SINGLE = 1;
    const SLIDER = 2;

    public static function getValues()
    {
        return [
            self::SINGLE,
            self::SLIDER
        ];
    }
}