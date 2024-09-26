<?php

namespace App\Enums;

class JapaneseProficiencyLevel
{
    // Constants representing the Japanese proficiency levels
    const N1 = 1;
    const N2 = 2;
    const N3 = 3;
    const N4 = 4;
    const N5 = 5;

    /**
     * Get an associative array of proficiency levels and their corresponding values.
     *
     * @return array
     */
    public static function getLevels()
    {
        return [
            'N1' => self::N1,
            'N2' => self::N2,
            'N3' => self::N3,
            'N4' => self::N4,
            'N5' => self::N5,
        ];
    }
}
