<?php

namespace App\Services;

use App\Models\FbReporting\Rpc;
use Carbon\Carbon;

class RpcService
{
    public function countKeyword($keyword, $market)
    {
        return Rpc::where('keyword', $keyword)
        ->where('market', $market)
        ->where('date', '>=', Carbon::now()->subDays(7))
        ->where('date', '<=', Carbon::now())
        // ->whereRaw('date BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) AND NOW()') // does not work while running unit test with sqlite
        ->where('rpc', '>', 0)
        ->where('tot_clicks', '>', 10)
        ->count();
    }


}