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
     * @param array|null $array
     * @param string $delimeter
     * 
     * @return string
     */
    public function generateStringFromArray(?array $array, string $delimeter): ?string
    {
        if (gettype($array) === 'array' && count($array) > 0) {
            return implode($delimeter, $array);
        }
        return null;
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

    /**
     * @param string $string
     * 
     * @return bool
     */
    public function isCapsLock(string $string): bool
    {
        return $string === strtoupper($string);
    }
    
    
}