<?php

namespace App\Labs;

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

   
}