<?php

namespace FbReporting\TypeDailyPerfWebsiteBreakDownTool;

use FbReporting\TypeDailyPerfWebsiteBreakDownTool\Resources\AllWebsitesBreakDown;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class TypeDailyPerfWebsiteBreakDownTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('type-daily-perf-website-break-down-tool', __DIR__.'/../dist/js/tool.js');
        Nova::style('type-daily-perf-website-break-down-tool', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('type-daily-perf-website-break-down-tool::navigation');
    }
}
