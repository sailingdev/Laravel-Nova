<?php 

namespace App\Services;

use App\Labs\StringManipulator;
use App\Models\FbReporting\TypeDailyPerf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TypeDailyPerfService
{
   
    /**
     * @var array
     */
    protected $totalSumCollector = [];

    /**
     * @var string
     */
    public $prefix = '$';

    /**
     * @var string
     */
    public $suffix = '%';

    /**
     * @var string
     */
    public $suffixInflection = false;

    /**
     * @var string
     */
    protected $startDate;

    /**
     * @var string
     */
    protected $endDate;

   
    public function loadDailySummaryByTags(?string $typeTag, $startDate=null, $endDate=null)
    {
        
        $this->setDates($startDate, $endDate); 
        $typeTagClause = $this->formatTypeTagClause($typeTag);
        
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
            ROUND( (SUM(tot_profit)/SUM(tot_spend)) * 100,2) AS 'tot_roi' 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'all'
            AND (date >= '$this->startDate' AND date <= '$this->endDate')
            $typeTagClause
            GROUP BY date ORDER BY date DESC) as results_all 

            LEFT OUTER JOIN
            (SELECT date, 
            SUM(tot_spend) AS yahoo_spend, 
            SUM(tot_revenue) AS yahoo_revenue, 
            SUM(tot_profit) AS yahoo_profit, 
            ROUND( (SUM(tot_profit)/SUM(tot_spend)) * 100 ,2) AS yahoo_roi 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'yahoo'
            AND (date >= '$this->startDate' AND date <= '$this->endDate')
            $typeTagClause 
            
            GROUP BY date ORDER BY date DESC) as results_yahoo 
            ON results_all.date = results_yahoo.date

            LEFT OUTER JOIN
            (SELECT date, 
            SUM(tot_spend) AS media_spend, 
            SUM(tot_revenue) AS media_revenue, 
            SUM(tot_profit) AS media_profit, 
            ROUND( (SUM(tot_profit)/SUM(tot_spend)) * 100, 2) AS media_roi 
            FROM fb_reporting.type_daily_perf
            WHERE feed = 'media'
            AND (date >= '$this->startDate' AND date <= '$this->endDate')
            $typeTagClause
            GROUP BY date ORDER BY date DESC) as results_media
            ON results_all.date = results_media.date
             
            "


        );
        
        // $query = $this->loadMock();

        // array_map(function($row) {
        //     $row->date = Carbon::parse($row->date)->toFormattedDateString();
        //     return $row;
        // }, $query);
       
        // session(['daily_summary_by_tags' => $query]);
         
        return $query;
    }


    /**
     * From a built up and loaded query, aggregate sums and data necessary for trend metrics
     * 
     * @param array $data
     * @param string $type
     * @param mixed $valueType="sum"
     * 
     * @return object
     */
    public function aggregateTrendMetricData(array $data, string $type, $valueType="sum"): object
    {
      
        $cpRows = $data;
        
        if ($type !== "tot_roi") {
            $totalSum = (float) array_reduce($cpRows, function ($agg, $val) use ($type) {
                return $agg + $val->{$type}; 
            }, 0);
        }
        else { 
            $totalSum = $this->totalSumCollector['tot_profit'] !== 0 && 
                $this->totalSumCollector['tot_spend'] != 0 ? 
                $this->totalSumCollector['tot_profit'] / $this->totalSumCollector['tot_spend'] 
                : 0;
        }
        
        // To avoid resumming for tot_roi, we store the sum totals in an array property so roi comes in last
        // and calculates from it 
        $this->totalSumCollector[$type] = $totalSum;
        
        $sm = new StringManipulator;
        $trend = new \stdClass;
        foreach ($cpRows as $row) {
            $trend->{$sm->formatDateToString($row->date)} = (float) $row->{$type};
        } 

        $metricObj = new \stdClass;
        $metricObj->value = $type != 'tot_roi' ?  $totalSum : round($totalSum * 100, 2);
        $metricObj->prefix = $valueType == "sum" ? $this->prefix : '';
        $metricObj->suffix = $valueType === "percentage" ? '%' : '';
        $metricObj->suffixInflection = $this->suffixInflection; 
        $metricObj->trend = $trend;
      
        return $metricObj;
    }

    /**
     * Make queried data ready for transport to the front end
     * 
     * @param mixed $lData
     * 
     * @return mixed
     */
    public function prepareData($lData)
    {
        
        $appendables = [
            'tot_spend' => $this->prefix,
            'tot_profit' => $this->prefix,
            'tot_revenue' => $this->prefix,
            'tot_roi' => $this->suffix,
            'yahoo_spend' => $this->prefix,
            'yahoo_profit' => $this->prefix,
            'yahoo_revenue' => $this->prefix,
            'yahoo_roi' => $this->suffix,
            'media_spend' => $this->prefix,
            'media_profit' => $this->prefix,
            'media_revenue' => $this->prefix,
            'media_roi' => $this->suffix,
            'cpa' => $this->prefix,
            'rpc' => $this->prefix
        ]; 
      
        if (count($lData) > 0) {
            foreach ($lData as $key => $rows) {
               
                // By default, objects are passed by reference while variables and arrays are passed by values
                // The copy of data at work here is an array with objects within
                // I discovered any attempt to alter the objects within this array leads to an alteration in the original 
                // copy that was passed
                // So by clone, I simply cloned a copy of that object into memory, replaced it with the local copy of the array at work
                // then use that to process the alteration
                // There could be better ways to deal with this problem though. For now, this should work
                
                $rows = clone $lData[$key];
                $lData[$key] = $rows; 
                
                foreach ($rows as $column => $value) {
                    if (array_key_exists($column, $appendables)) {
                       $prepVal = $value === null ? 0 : $value;
                       $lData[$key]->{$column} = $appendables[$column] === $this->prefix ? 
                            $appendables[$column] . $prepVal : $prepVal . $this->suffix; 
                    }
                }
            } 
        }
         
        return $lData;
    }

    /**
     * @param mixed $startDate
     * @param mixed $endDate
     * 
     * @return this
     */
    protected function setDates($startDate, $endDate)
    {
        $this->endDate = $endDate === null ? date('Y-m-d') : $endDate;

        if ($endDate !== null) {
            try {
                Carbon::parse($endDate);
            } catch (\Exception $e) { 
               abort(422, 'End date is not a valid date');
            }
        } 

        if ($startDate !== null) {
            try {
                Carbon::parse($endDate);
                $this->startDate = $startDate;
            } catch (\Exception $e) {
               abort(422, 'Start date is not a valid date');
            }
        }
        else {
            $dateSp = date('d') - 1;
            // if today happens to be first day of the month, it means end date should also be today since by default,
            // we are only loading the data for all the days of the current month
            $this->startDate = date('d') === 1 ? $endDate : 
                date('Y-m-d', strtotime('-' . $dateSp . ' days', strtotime(date('Y-m-d')) ) );
        }
        
        return $this;
    }

    /** 
     * @param string $typeTags
     * 
     * @return string
     */
    protected function formatTypeTagClause(?string $typeTags): string
    {
        if ($typeTags !== null) {
            $sm = new StringManipulator;
            $exploded = $sm->generateArrayFromString($typeTags, ',');
            $str = '';
            foreach ($exploded as $key => $value) {
                $itr = count($exploded) - 1;
                $str .= $key != $itr ?  "'". $value. "'," :  "'". $value. "'";
            }
           
            return "AND type_tag IN (" . $str . ")";
        } 
        return '';
    }


    public function getAllTypeTags()
    {
        $typeTags = [];
        // TypeDailyPerf::select('type_tag')->distinct('type_tag')->chunk(5000000, function($query) {
        //     foreach ($query as $value) {
        //         $typeTags[] = $query->type_tag;
        //     }
        // });

        foreach (TypeDailyPerf::select('type_tag')->groupBy('type_tag')->cursor() as $query) {
            $typeTags[] = $query->type_tag;
        }

        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // for ($i = 0; $i < 1000; $i++) {
        //     $characters = str_shuffle($characters);
        //     // $typeTags[] = ucfirst(substr($characters, 0, 10));
        //     array_push($typeTags, ucfirst(substr($characters, 0, 10)));
        // }
       
        return $typeTags;
    }

    /**
     * @TODO:: I need to rewrite this code
     * 
     * @param array $typeTag=[]
     * @param mixed $startDate
     * @param mixed $endDate
     * 
     * @return [type]
     */
    public function loadWebsiteDailySummary(?string $typeTag, $startDate, $endDate)
    {
        $this->setDates($startDate, $endDate);
        $typeTagClause = $this->formatTypeTagClause($typeTag);
        $websiteBreakdown = [];
        
        $websites = DB::select("SELECT site
            FROM fb_reporting.type_daily_perf 
            WHERE site != 'all' 
            AND (date >= '$this->startDate' AND date <= '$this->endDate')
            $typeTagClause
            GROUP BY site ORDER BY site DESC
        ");
        
        foreach ($websites as $key => $row) {
            $query = DB::select("SELECT date, 
                SUM(tot_spend) AS tot_spend, 
                SUM(tot_revenue) AS tot_revenue, 
                SUM(tot_profit) AS tot_profit, 
                ROUND( (SUM(tot_profit)/SUM(tot_spend)) * 100,2) AS tot_roi
                    FROM fb_reporting.type_daily_perf 
                    WHERE site = '$row->site' 
                    AND (date >= '$this->startDate' AND date <= '$this->endDate')
                    $typeTagClause
                    GROUP BY date ORDER BY date DESC
                ");
                
                
            $websiteBreakdown[] = [
                'website' => $row->site,
                'totals' => $this->prepareData($query)
            ];
        }
         
        return $websiteBreakdown;
    }

    /**
     * @param null|string $typeTag
     * @param mixed $startDate
     * @param mixed $endDate
     * 
     * @return array
     */
    public function loadCampaignDailySummary(?string $typeTag, $startDate, $endDate)
    {
        $this->setDates($startDate, $endDate);
        $typeTagClause = $this->formatTypeTagClause($typeTag);
            
        $query = DB::select("SELECT type_tag AS 'Type Tag',
            SUM(tot_spend) AS tot_spend,
            SUM(tot_revenue) AS tot_revenue,
            SUM(tot_profit) AS tot_profit, 
            ROUND( (SUM(tot_profit)/SUM(tot_spend) * 100), 1) AS tot_roi, 
            SUM(tot_clicks) AS tot_clicks, 
            ROUND(SUM(tot_revenue)/SUM(tot_clicks), 1) AS rpc, 
            ROUND(SUM(tot_spend)/SUM(tot_clicks), 1) AS cpa
            FROM fb_reporting.type_daily_perf
            WHERE
            (date >= '$this->startDate' AND date <= '$this->endDate')
                AND site = 'all'
                $typeTagClause
                GROUP BY type_tag
            "
        ); 
        
        $data =  $this->prepareData($query);
        session(['campaign_break_down' => $data ]);

        return $data;
    }


























    /**
     * to be deleted. Only used when unable to get data from the Database
     */
    public function loadMock()
    {
        $query = []; 
    
        for($i = 1; $i <= 23; $i++)
        {
            $newObj = new \stdClass;
            $newObj->date = date('Y-m-d', strtotime('-'.$i.' days', strtotime(date('Y-m-d'))));
            $newObj->tot_spend = mt_rand(5000, 8000);
            $newObj->tot_revenue = mt_rand(16000, 21000);
            $newObj->tot_profit = mt_rand(6000, 14000);
            $newObj->tot_roi =  round($this->randFloat() * 100, 2);

            $newObj->yahoo_spend = mt_rand(2000, 3000);
            $newObj->yahoo_revenue = mt_rand(4000, 9000);
            $newObj->yahoo_profit = mt_rand(3000, 7000);
            $newObj->yahoo_roi =  round($this->randFloat() * 100, 2);

            $newObj->media_spend = mt_rand(2000, 4000);
            $newObj->media_revenue = mt_rand(3000, 8000);
            $newObj->media_profit = mt_rand(4000, 8000);
            $newObj->media_roi =  round($this->randFloat() * 100, 2);
            $query[] = $newObj;
        }
        return $query;
    }

    /**
     * to be deleted. Only used when unable to get data from the Database
     */
    function randFloat($st_num=0,$end_num=1,$mul=1000000)
    {
        if ($st_num>$end_num) return false;
        return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
    }
}