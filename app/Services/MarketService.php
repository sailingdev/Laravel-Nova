<?php

namespace App\Services;

use App\Models\FbReporting\Market;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class MarketService
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection  
     */
    public function getAll(): Collection
    {
        return Cache::remember('markets', 3600, function () {
            return Market::all();
        });
    }

    /**
     * @param string $code
     * 
     * @return int|null
     */
    public function getMarketIdbyCode(string $code): ?int
    {
        $market = Market::select('id')->where('code', $code)->first();
        if ($market == null) {
            return null;
        }
        return $market->id;
    }
}