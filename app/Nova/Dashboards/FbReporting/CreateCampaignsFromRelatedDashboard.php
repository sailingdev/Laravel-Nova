<?php

namespace App\Nova\Dashboards\FbReporting;

use FbReporting\CreateCampaignsFromRelatedCard\CreateCampaignsFromRelatedCard;
use Laravel\Nova\Dashboard;

class CreateCampaignsFromRelatedDashboard extends Dashboard
{
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
       return 'Create Campaigns From Related';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new CreateCampaignsFromRelatedCard())
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fb-reporting-create-campaigns-from-related';
    }
}
