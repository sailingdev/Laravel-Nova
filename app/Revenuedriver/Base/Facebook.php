<?php

namespace App\Revenuedriver\Base;
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
}