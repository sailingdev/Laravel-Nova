<?php

namespace App\Nova\Dashboards\FbReporting;

use FbReporting\TypeDailyPerfCard\TypeDailyPerfCard;
use FbReporting\TypeDailyPerfWebsiteBreakDownCard\TypeDailyPerfWebsiteBreakDownCard;
use Laravel\Nova\Dashboard;

class WebsiteBreakDownDashboard extends Dashboard
{
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'Website Break Down';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [ 
            new TypeDailyPerfWebsiteBreakDownCard()
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fb-reporting-website-break-down';
    }
}
