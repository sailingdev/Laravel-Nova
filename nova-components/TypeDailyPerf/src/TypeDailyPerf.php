<?php

namespace FbReporting\TypeDailyPerf;

use Laravel\Nova\Card;

class TypeDailyPerf extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';
    

    public function tableSummary()
    {
        return $this->withMeta([
            
        ]);
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'type-daily-perf';
    }
}
