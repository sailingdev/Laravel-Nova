<?php

namespace App\Revenuedriver\Base;

use App\Labs\StringManipulator;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use Illuminate\Support\Facades\App;

abstract class Facebook
{
    /**
     * @var string
    */
    public $appId;

    public $clientSecret;

    public $clientToken;
 
    /**
     * @var string
     */
    public $api;

    /**
     * @var string
    */
    protected $account3 = 'act_370837070102255';

    /**
     * @var string
    */
    protected $account21 = 'act_426462675029843';

    /**
     * @var string
    */
    protected $account30 = 'act_426162348442901';
    
    
    public function __construct()
    {
        $this->appId =  config('facebook.marketing.app_id');
        $this->clientSecret = config('facebook.marketing.client_secret');
        $this->clientToken = config('facebook.marketing.client_token');
         
        Api::init($this->appId, $this->clientSecret, $this->clientToken, false);

        // The Api object is now available through singleton
        $this->api = Api::instance(); 
        $this->api->setLogger(new CurlLogger());
    }


    /**
     * @param string $campaignName
     * @param mixed $type=null
     * 
     * @return mixed
     */
    public function extractDataFromCampaignName(string $campaignName, $type=null)
    {
        $data = [];
        $startPos = strpos($campaignName, '{') + 1; $endPos = strpos($campaignName, '}');
        $preped = substr($campaignName, $startPos, $endPos - $startPos);
        $data['site'] =  $data['type_tag'] = $data['keyword'] = $data['market'] = '';

        if (strlen($preped) > 0) {
            $sm = new StringManipulator;
            $extracts = $sm->generateArrayFromString($preped, ',');
            $data['site'] = $extracts[0];
            $data['type_tag'] = array_key_exists(1, $extracts) ? $extracts[1] : '';
            $data['keyword'] = array_key_exists(2, $extracts) ? preg_replace('#[^a-z0-9 ]#i', " ", $extracts[2]) : '';
            $data['market'] = array_key_exists(3, $extracts) ? $extracts[3] : '';
        }

        // feed
        $startPos = strpos($campaignName, '(') + 1; $endPos = strpos($campaignName, ')');
        $data['feed'] = substr($campaignName, $startPos, $endPos - $startPos);

        if ($type !== null) {
            return $data[$type];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function determineStartTime()
    {  
        //next saturday
        if (date('l') == 'Saturday') {
            return date('Y-m-d');
        }
        return date('Y-m-d', strtotime('next Saturday'));
    }

    /**
     * @param string $keyword
     * @param string $market
     * @param string $feed
     * @param string $site
     * @param string $typeTag
     * 
     * @return string
     */
    public function formatCampaignName(string $keyword, string $market, string $feed, string $site, string $typeTag): string
    {
        return preg_replace("#[^a-z0-9]#i", "_", $keyword) . " - " .
            $market . 
            " (".$feed.")" . 
            " {" .$site . "," .$typeTag . "," .preg_replace("#[^a-z0-9]#i", "+", $keyword) . "," . $market ."}";
    }

    /**
     * @return string
     */
    public function getTargetAccount(): string
    {
        return $this->account30;
        if (App::environment('production')) {
            return $this->account21;
        }
        return $this->account30;
    }

    /**
     * @param string $status
     * 
     * @return string
     */
    public function determineStatus(string $status): string
    {
        return 'PAUSED';
        if (App::environment('production')) {
            return $this->status;
        }
        return 'PAUSED';
    }
    
    /**
     * @return string
     */
    public function getAccount21Id(): string
    {
        return $this->account21;
    }

    /**
     * @return string
     */
    public function getAccount3Id(): string
    {
        return $this->account3;
    }
}