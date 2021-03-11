<?php 

namespace App\Labs;

use Carbon\Carbon;

class Utility 
{
    /**
     * @return string
     */
    public static function sectionOfDay(): string
    { 
        if (Carbon::now()->setTimezone('Europe/Vatican')->hour < 12) {
            return 'morning';
        }
        else if (Carbon::now()->setTimezone('Europe/Vatican')->hour >= 12 && Carbon::now()->setTimezone('Europe/Vatican')->hour < 16) {
            return 'afternoon';
        }
        else {
            return 'evening';
        }
    }
}