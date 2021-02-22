<?php

namespace App\Nova\Dashboards\FbReporting;

use Laravel\Nova\Dashboard;

class SubmitKeywordsDashboard extends Dashboard
{
    
     /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
     public static function label()
     {
        return 'Submit Keywords';
     }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new SubmitKeywordsCard())
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fb-reporting-submit-keywords';
    }
}
