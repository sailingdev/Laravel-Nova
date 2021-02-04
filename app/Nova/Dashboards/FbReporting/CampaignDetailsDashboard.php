<?php

namespace App\Nova\Dashboards\FbReporting;

use FbReporting\TypeDailyPerfCampaignDetailsCard\TypeDailyPerfCampaignDetailsCard;
use Laravel\Nova\Dashboard;

class CampaignDetailsDashboard extends Dashboard
{
     /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'Campaign Details';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new TypeDailyPerfCampaignDetailsCard())->dailyTotalsByTag()
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fb-reporting-campaign-details';
    }
}
