<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookCampaign extends Facebook
{

    public function loadCampaign($accountId, $fields=[]): array
    { 
        $account = new AdAccount($this->accountId); 
        $cursor = $account->getCampaigns($fields);
        if ($cursor->count() < 1) {
            return [false, 'No record for this campaign'];
        }
        return [true, $cursor];
    }

    protected function processFields(array $fields): array
    {
        // $formattedFields = [];
        // if (count($fields) > 0) {
        //     foreach ($fields as $field) {
        //         array_push($formattedFields,  CampaignFields::strtoupper($field));
        //     }
        // }
        // return $formattedFields;
    }
}