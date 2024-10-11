<?php

namespace App\Enums;

class BannerGroup
{
    const LOGIN = 1;
    const HOME = 2;
    const CONTACT = 3;
    const COURSE_DETAIL = 4;

    public static function getValues()
    {
        return [
            self::LOGIN,
            self::HOME,
            self::CONTACT,
            self::COURSE_DETAIL,
        ];
    }
}