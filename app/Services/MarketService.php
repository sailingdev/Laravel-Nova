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
}