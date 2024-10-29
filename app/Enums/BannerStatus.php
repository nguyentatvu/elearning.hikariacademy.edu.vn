<?php

namespace App\Enums;

class BannerStatus
{
    const ACTIVE = 1;
    const NONACTIVE = 0;

    public static function getValues()
    {
        return [
            self::ACTIVE,
            self::NONACTIVE
        ];
    }
}