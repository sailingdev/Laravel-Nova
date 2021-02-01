<?php

namespace App\Labs;

use Carbon\Carbon;

class StringManipulator
{

    /**
     * @param string $string
     * @param string $delimeter
     * 
     * @return array
     */
    public function generateArrayFromString(?string $string, string $delimeter): array
    {
        if ($string !== null && !empty($string) ) {
            return explode($delimeter, $string);
        } 
        return [];
    }

    /**
     * @param string $date
     * 
     * @return string
     */
    public function formatDateToString(string $date): string
    {
        return Carbon::parse($date)->toFormattedDateString();
    }
}