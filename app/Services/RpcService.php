<?php

namespace App\Services;

use App\Models\FbReporting\Rpc;

class RpcService
{
    public function countKeyword($keyword, $market)
    {
        return Rpc::where('keyword', $keyword)
        ->where('market', $market)
        ->whereRaw('date BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) AND NOW()')
        ->where('rpc', '>', 0)
        ->where('tot_clicks', '>', 10)
        ->count();
    }


}