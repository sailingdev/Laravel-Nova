<?php

namespace App\Revenuedriver\Base;

use App\Labs\StringManipulator;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;

abstract class Facebook
{
    /**
     * @var string
    */
    public $appId;

    public $clientSecret;

    public $clientToken;

    public $accountId;

    public $api;

    public function __construct()
    {
        $this->appId =  config('facebook.marketing.app_id');
        $this->clientSecret = config('facebook.marketing.client_secret');
        $this->clientToken = config('facebook.marketing.client_token');
        
        $this->accountId = config('facebook.marketing.account_id');

        Api::init($this->appId, $this->clientSecret, $this->clientToken);

        // The Api object is now available through singleton
        $this->api = Api::instance(); 
        $this->api->setLogger(new CurlLogger());
    }

    public function extractDataFromCampaignName(string $campaignName)
    {
        $startPos = strpos($campaignName, '{') + 1; $endPos = strpos($campaignName, '}');
        $preped = substr($campaignName, $startPos, $endPos - $startPos);
        if (strlen($preped) > 0) {
            $sm = new StringManipulator;
            $extracts = $sm->generateArrayFromString($preped, ',');
        }
        return [
            'site' => $extracts[0],
            'type_tag' => $extracts[1],
            'keyword' => preg_replace('#[^a-z0-9 ]#i', " ", $extracts[2]),
            'market' => $extracts[3]
        ];
    }
}