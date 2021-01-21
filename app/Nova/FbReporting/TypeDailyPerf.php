<?php

namespace App\Nova\FbReporting;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\FbReporting\TagsDailyTotalProfit;
use App\Nova\Metrics\FbReporting\TagsDailyTotalRevenue;
use App\Nova\Metrics\FbReporting\TagsDailyTotalRoi;
use App\Nova\Metrics\FbReporting\TagsDailyTotalSpend;
use App\Nova\Resource;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Sparkline;

class TypeDailyPerf extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\FbReporting\TypeDailyPerf::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'date', 'tot_spend', 'tot_revenue', 'tot_profit', 'tot_roi'
    ];

    public static function label() {
        return 'Daily Totals';
    }

    public static function uriKey()
    {
        return 'dashboard-main';
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        // dd($query->groupBy('date')->get('date'));
        // return $query->distinct()->orderBy('date', 'desc')->get;
// AND $__timeFilter(date)
// AND type_tag IN ($type_tag)

        return $query;

        return $query = DB::raw(
            
            "SELECT DATE_FORMAT(results_all.date, '%Y-%m-%d') AS `Date`,results_all.'Tot Spend',results_all.'Tot Revenue',
            results_all.'Tot Profit',results_all.'Tot ROI',results_yahoo.'Yahoo Spend',results_yahoo.'Yahoo Revenue',
            results_yahoo.'Yahoo Profit',results_yahoo.'Yahoo ROI',results_media.'Media Spend',results_media.'Media Revenue',
            results_media.'Media Profit',results_media.'Media ROI' FROM 
            
            (SELECT date, SUM(tot_spend) AS 'Tot Spend', SUM(tot_revenue) AS 'Tot Revenue', SUM(tot_profit) AS 'Tot Profit', SUM(tot_profit)/SUM(tot_spend) AS 'Tot ROI' 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'all'
            
            
            GROUP BY date) as results_all 
            LEFT OUTER JOIN
            (SELECT date, SUM(tot_spend) AS 'Yahoo Spend', SUM(tot_revenue) AS 'Yahoo Revenue', SUM(tot_profit) AS 'Yahoo Profit', SUM(tot_profit)/SUM(tot_spend) AS 'Yahoo ROI' 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'yahoo'
             
            
            GROUP BY date) as results_yahoo 
            ON results_all.date = results_yahoo.date
            LEFT OUTER JOIN
            (SELECT date, SUM(tot_spend) AS 'Media Spend', SUM(tot_revenue) AS 'Media Revenue', SUM(tot_profit) AS 'Media Profit', SUM(tot_profit)/SUM(tot_spend) AS 'Media ROI' 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'media'
          
            GROUP BY date) as results_media
            ON results_all.date = results_media.date"

        );

         

    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Date::make('date', 'date')->exceptOnForms()->sortable(),
            Number::make('Total Spend', function () {
                return $this->tot_spend.' '.$this->tot_revenue;
            })->exceptOnForms(),

            // Number::make('Total Spend', 'tot_spend')->exceptOnForms()->sortable(),
            // Number::make('Total Revenue', 'tot_revenue')->exceptOnForms()->sortable(),
            // Number::make('Total Profit', 'tot_profit')->exceptOnForms()->sortable(),
            // Number::make('Total ROI', 'tot_roi')->exceptOnForms()->sortable(), 
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
              
            (new TagsDailyTotalSpend())->width('1/4'),
            (new TagsDailyTotalRevenue())->width('1/4'),
            (new TagsDailyTotalProfit())->width('1/4'),
            (new TagsDailyTotalRoi())->width('1/4'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
