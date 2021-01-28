<?php

namespace App\Nova\Metrics\FbReporting;

use App\Models\FbReporting\FbSpend;
use App\Models\FbReporting\TypeDailyPerf;
use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendDateExpressionFactory;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Nova;

class TagsDailyTotalRoi extends Trend
{
    
    public $name = 'Total ROI';

    public $precision = 5;

    /**
     * Calculate the value of the metric.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    { 
        $trendQuery = $this->sumRoi($request, TypeDailyPerf::where('site', 'all'), 'tot_revenue');
        
        $sumQuery = DB::select("SELECT SUM(tot_profit)/SUM(tot_spend) AS tot_roi FROM fb_reporting.type_daily_perf
        WHERE (DATE(date) BETWEEN '2021-01-1 00:00:00' AND '2021-01-23 11:59:59')
            AND site = 'all'
        "); 

        $trendResult = new TrendResult;
        $total = (float) $sumQuery[0]->tot_roi * 100;
        
        $trendResult->value = round($total, 2);
        $trendResult->suffix = '%';
        $trendResult->suffixInflection = false;
        $trendResult->trend = $trendQuery->trend;
        return $trendResult;
    }

    /**
     * Return a value result showing a sum aggregate over days.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function sumRoi($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_DAYS, 'sum', $column, $dateColumn);
    }

    protected function aggregate($request, $model, $unit, $function, $column, $dateColumn = null)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;
       
        $expression = (string) TrendDateExpressionFactory::make(
            $query, $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn(),
            $unit, $timezone
        );
        
        $possibleDateResults = $this->getAllPossibleDateResults(
            $startingDate = $this->getAggregateStartingDate($request, $unit),
            $endingDate = Chronos::now(),
            $unit,
            $timezone,
            $request->twelveHourTime === 'true'
        );

        $wrappedColumn = $column instanceof Expression
                ? (string) $column
                : $query->getQuery()->getGrammar()->wrap($column);
      
        $secondColumn = 'tot_spend';
        // $results = $query
        //         ->select(DB::raw("{$expression} as date_result, {$function}({$wrappedColumn})/{$function}({$secondColumn}) as aggregate"))
               
        //         ->whereBetween(
        //             $dateColumn, array_map(function ($date) {
        //                 return $this->asQueryDatetime($date);
        //             }, [$startingDate, $endingDate])
        //         )->groupBy(DB::raw($expression))
        //         ->orderBy('date_result')
        //         ->get();
        // SUM(tot_profit)/SUM(tot_spend)
       
        $results = $query
        ->select(DB::raw("{$expression} as date_result, 
            SUM(tot_profit) as `tot_profit`, SUM(tot_spend) as `tot_spend`, SUM(tot_profit)/SUM(tot_spend) as `aggregate`"))
        ->whereBetween(
            $dateColumn, array_map(function ($date) {
                return $this->asQueryDatetime($date);
            }, [$startingDate, $endingDate])
        )
        ->groupBy(DB::raw($expression))
        ->orderBy('date_result')
        ->get();
         
        $results = array_merge($possibleDateResults, $results->mapWithKeys(function ($result) use ($request, $unit) {
            return [$this->formatAggregateResultDate(
                $result->date_result, $unit, $request->twelveHourTime === 'true'
            ) => round($result->aggregate, $this->precision)];
        })->all());

        if (count($results) > $request->range) {
            array_shift($results);
        }
       
        return $this->result()->trend(
            $results
        );
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
        return 'fb-reporting-tags-daily-total-roi';
    }
}
