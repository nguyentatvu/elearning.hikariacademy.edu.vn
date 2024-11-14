<?php

namespace App\Enums;

class LearningStatus
{
    const COMPLETED = 1;
    const IN_PROGRESS = 0;
    const NOT_COMPLETED = 2;

    /**
     * Get an associative array of proficiency levels and their corresponding values.
     *
     * @return array
     */
    public static function getLevels()
    {
        return [
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::NOT_COMPLETED => 'Not Completed',
        ];
    }
}
