<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookCampaign extends Facebook
{

    public function loadCampaign($accountId, $fields=[])
    { 
        $account = new AdAccount($this->accountId);

        dd($account);
    }
}