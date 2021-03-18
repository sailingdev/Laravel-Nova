<?php

namespace App\Services;

use App\Models\FbReporting\Market;
use Illuminate\Support\Facades\Cache;

class MarketService
{
    /**
     * @return
     */
    public function getAll()
    {
        return Cache::remember('markets', 3600, function () {
            return Market::all();
        });
    }

    public function getMarketIdbyCode($code)
    {
        $market = Market::select('id')->where('code', $code)->first();
        if ($market == null) {
            return null;
        }
        return $market->id;
    }
}