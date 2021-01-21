<?php

namespace App\Nova\Metrics\FbReporting;

use App\Models\FbReporting\FbSpend;
use App\Models\FbReporting\TypeDailyPerf;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class TagsDailyTotalRevenue extends Trend
{
    
    public $name = 'Total Revenue';

    /**
     * Calculate the value of the metric.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    { 
        return $this->sumByDays($request, TypeDailyPerf::where('site', 'all'), 'tot_revenue')
        ->showSumValue()
        ->dollars();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            date('d') => __('This Month'),
            30 => __('30 Days'),
            60 => __('60 Days'),
            90 => __('90 Days'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(30);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'fb-reporting-tags-daily-total-revenue';
    }
}
