<?php

namespace App\Nova\Dashboards\FbReporting;

use FbReporting\FbPagePostsCard\FbPagePostsCard;
use Laravel\Nova\Dashboard;

class FbPagePostsDashboard extends Dashboard
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
            new FbPagePostsCard()
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fb-reporting-fb-page-posts-dashboard';
    }
}
