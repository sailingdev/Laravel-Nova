<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TypeDailyPerfService
{
    
    public function getDailySummary(string $tags='all', string $range="month")
    {
        $query = DB::select(
            
            "SELECT DATE_FORMAT(results_all.date, '%Y-%m-%d') AS `date`,
            results_all.tot_spend,
            results_all.tot_revenue,
            results_all.tot_profit,
            results_all.tot_roi,
            results_yahoo.yahoo_spend,
            results_yahoo.yahoo_revenue,
            results_yahoo.yahoo_profit,
            results_yahoo.yahoo_roi,
            results_media.media_spend,
            results_media.media_revenue,
            results_media.media_profit,
            results_media.media_roi
            FROM 
            
            (SELECT date, 
            SUM(tot_spend) AS tot_spend, 
            SUM(tot_revenue)AS tot_revenue, 
            SUM(tot_profit) AS tot_profit, 
            ROUND( (SUM(tot_profit)/SUM(tot_spend)) * 100,1) AS 'tot_roi' 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'all'
            AND (DATE(date) BETWEEN '2021-01-1 00:00:00' AND '2021-01-21 11:59:59')
            -- WHERE TAG HERE
            GROUP BY date ORDER BY date DESC) as results_all 

            LEFT OUTER JOIN
            (SELECT date, 
            SUM(tot_spend) AS yahoo_spend, 
            SUM(tot_revenue) AS yahoo_revenue, 
            SUM(tot_profit) AS yahoo_profit, 
            ROUND( (SUM(tot_profit)/SUM(tot_spend)) * 100,1) AS yahoo_roi 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'yahoo'
            AND (DATE(date) BETWEEN '2021-01-1 00:00:00' AND '2021-01-21 11:59:59')
            -- WHERE TAG HERE 
            
            GROUP BY date ORDER BY date DESC) as results_yahoo 
            ON results_all.date = results_yahoo.date

            LEFT OUTER JOIN
            (SELECT date, 
            SUM(tot_spend) AS media_spend, 
            SUM(tot_revenue) AS media_revenue, 
            SUM(tot_profit) AS media_profit, 
            ROUND( (SUM(tot_profit)/SUM(tot_spend)) * 100,1) AS media_roi 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'media'
            AND (DATE(date) BETWEEN '2021-01-1 00:00:00' AND '2021-01-21 11:59:59')
            -- WHERE TAG HERE
            GROUP BY date ORDER BY date DESC) as results_media
            ON results_all.date = results_media.date"

        );
        session(['daily_summary' => $query]);
        return $query;
    }
}