<?php

namespace App\Nova\Dashboards\FbReporting;

use Laravel\Nova\Dashboard;

class CreateCampaignsFromTemplateDashboard extends Dashboard
{
     /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
       return 'Fb Page Posts';
    }

    
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            //
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fb-reporting/-create-campaigns-from-template-dashboard';
    }
}
