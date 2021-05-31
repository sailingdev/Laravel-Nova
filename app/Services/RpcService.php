<?php

namespace App\Services;

use App\Models\FbReporting\Rpc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RpcService
{
    public function countKeyword($keyword, $market, $feed)
    {
        return Rpc::where('keyword', $keyword)
        ->where('market', $market)
        ->where('date', '>=', Carbon::now()->subDays(7))
        ->where('date', '<=', Carbon::now())
        ->where('feed', $feed)
        // ->whereRaw('date BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) AND NOW()') // does not work while running unit test with sqlite
        ->where('rpc', '>', 0)
        ->where('tot_clicks', '>', 10)
        ->count();
    }

    /**
     * @param string $market
     * @param string $feed
     * 
     * @return float
     */
    public function averageRpcOfMarketInLast7Days(string $market, string $feed)
    { 
        $avg = DB::select(
            "SELECT AVG(NULLIF(rpc, 0)) AS avg
            FROM fb_reporting.rpc
            WHERE feed = '$feed'
            AND market = '$market'
            AND date BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) AND NOW()");
            
        return $avg[0]->avg;
    }

}