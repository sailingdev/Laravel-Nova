<?php

namespace App\Nova\Dashboards\FbReporting;

use FbReporting\IgAccountLoaderCard\IgAccountLoaderCard;
use Laravel\Nova\Dashboard;

class IgAccountLoaderDashboard extends Dashboard
{

     /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
       return 'Load IG Account IDs';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new IgAccountLoaderCard()
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fb-reporting-ig-account-loader-dashboard';
    }
}
