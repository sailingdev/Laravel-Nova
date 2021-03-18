<?php

namespace App\Services;

use App\Models\FbReporting\AdText;
use Illuminate\Support\Facades\Cache;

class AdTextService
{
    /**
     * @return
     */
    public function getRandomDataByMarketId($marketId, $limit=2)
    {
        return AdText::where('market_id', $marketId)->inRandomOrder()->limit($limit)->get();
    }
}