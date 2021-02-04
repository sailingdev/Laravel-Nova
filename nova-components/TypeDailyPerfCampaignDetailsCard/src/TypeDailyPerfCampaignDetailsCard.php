<?php

namespace FbReporting\TypeDailyPerfCampaignDetailsCard;

use Laravel\Nova\Card;

class TypeDailyPerfCampaignDetailsCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    public function dailyTotalsByTag()
    {
        return $this->withMeta([
            'metricWidth' => ['1/4', '1/4', '1/4']
        ]);
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'type-daily-perf-campaign-details-card';
    }
}
