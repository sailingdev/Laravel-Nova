<?php

namespace App\Revenuedriver;

use App\Models\FbReporting\AdLocale;
use App\Revenuedriver\Base\Facebook;
use Illuminate\Support\Facades\Http;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookAdLocale extends Facebook
{ 

    /**
     * @var int
    */
    protected $getAllAttempt = 0;

   
    /**
     * @param mixed $marketId
     * 
     * @return string
     */
    public function getMarketLocale($marketId): string
    {
        $locale = AdLocale::select('locales')->where('market_id', $marketId)->first();
        if ($locale === null) {
            return 6;
        } 
        return $locale->locales;
    }

    
}