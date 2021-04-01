<?php

namespace App\Revenuedriver\Base;

use App\Labs\StringManipulator;
use App\Services\AdTextService;
use App\Services\MarketService;
use Carbon\Carbon;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use Illuminate\Support\Facades\App;

abstract class Facebook
{ 

    /**
     * @var string
    */
    public $appId;

    public $appSecret;

    public $appAccessToken;
 
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
        $this->appSecret = config('facebook.marketing.app_secret');
        $this->appAccessToken = config('facebook.marketing.app_access_token');
       
        Api::init($this->appId, $this->appSecret, $this->appAccessToken, false);
        
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
            $data['keyword'] = array_key_exists(2, $extracts) ?  $this->formatKeyword($extracts[2], " ") : '';
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
    public function determineStartTime($accountTimezone="UTC")
    {    
        $start = $accountTimezone === "UTC" ? Carbon::parse('next Saturday') : 
            Carbon::parse('next saturday', 'America/Los_Angeles');
    
        return $accountTimezone === "UTC" ? $start->toDateTimeString(): 
            Carbon::parse($start)->setTimezone('UTC')->toDateTimeString();
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
        $name = $this->formatKeyword($keyword, '_') . " - " .
            strtoupper($market) . 
            " (".ucfirst(strtolower($feed)).")" . 
            " {" .strtolower($site) . "," .strtolower($typeTag) . "," .$this->formatKeyword(strtolower($keyword), '+') . "," . strtoupper($market) ."}";
        
        return $name;
    }

     
    /**
     * @param string $keyword
     * @param string $market
     * @param mixed $createType=null
     * @param mixed $siteTag=null
     * @param mixed $dupNo=null
     * 
     * @return string
     */
    public function generateTypeTag(string $keyword, string $market, $createType=null, $siteTag=null, $dupNo=null): string
    {
        $sm = new StringManipulator;
        $dateSPlit = $sm->generateArrayFromString($this->determineStartTime(), '-');
        
        $datePrep = $dateSPlit[2] . $dateSPlit[1] . substr($dateSPlit[0], 2, 3);
        $keywordPrep =  $this->formatKeyword($keyword, '_');

        if ($createType === "related") {
            $typeTag =  $keywordPrep. '_' 
            . strtoupper($market) . '_' .
            $datePrep . '_' .
            'r' . '_' . 
            '009';
        }
        else if ($createType === "template") {
            $typeTag = $keywordPrep . '_' 
            . strtoupper($market) . '_' .
            $datePrep . '_' . 
            '009';
        }
        else {
            $typeTag = $keywordPrep . '_' . 
            strtoupper($market) . '_' .
            strtoupper($siteTag) . '_' .
            strtoupper($dupNo) . '_' . 
            $datePrep . '_' . 
            '009';
        } 
        return $typeTag;
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



    /**
     * @param string $keyword
     * 
     * @return string
     */
    public function formatKeyword(string $keyword, $replacer='_'): string
    {
        return strtolower(preg_replace("#[^a-z0-9]#i", $replacer, trim($keyword)));
    }


    public function getAccountSite(string $accountId): string
    {
        // for now, since we are ONLY using account 30 during dev and 21 during prod, and both are mapped to the worldbestsaver site, 
        // use as default
        return 'worldbestsaver.com';
    }

    /**
     * @return array
     */
    protected function siteData($market=null)
    {
        return [
            'worldbestsaver.com' => [
                'landing_page' => 'https://results.worldbestsaver.com',
                'feed' => 'media'
            ],
            'smartysavers.com' => [
                'landing_page' => 'https://' . $market . '.smartysavers.com',
                'feed' => 'yahoo'
            ],
        ];
    }

    /**
     * @param string $site
     * 
     * @return array
     */
    public function getSiteData(string $site, $market): array
    {
        if (array_key_exists($site, $this->siteData($market))) {
            return $this->siteData()[$site];
        };
        return [];
    }

    
    public function generateAdCreativeWebsiteUrl(string $accountId, string $keyword, string $typeTag, string $market) 
    {
        $mainSite = $this->getAccountSite($accountId);
        $mainSiteData = $this->getSiteData($mainSite, $market);

       
        if ($mainSiteData['feed'] === 'media') {
            return $this->makeMediaFeedWebsiteUrl($mainSite, $mainSiteData['landing_page'], $keyword, $typeTag, $market);
        }
        else if ($mainSiteData['feed'] === 'yahoo') {
            return $this->makeYahooFeedWebsiteUrl($mainSite, $mainSiteData['landing_page'], $keyword, $typeTag, $market);
        }
        return $this->makeMediaFeedWebsiteUrl($mainSite, $mainSiteData['landing_page'], $keyword, $typeTag, $market);
      
    }
 
    /**
     * @param string $mainSite
     * @param string $landingPage
     * @param string $keyword
     * @param string $typeTag
     * @param string $market
     * 
     * @return string
     */
    private function makeMediaFeedWebsiteUrl(string $mainSite, string $landingPage, string $keyword, string $typeTag, string $market): string
    {
        return $landingPage . '/search/?q=' . $this->formatKeyword($keyword, '+') . 
        '&p=5&chnm=facebook&chnm2=fb_' . $mainSite . '_' . strtolower($market) . '&chnm3='.$typeTag;
    }

    /**
     * @param string $mainSite
     * @param string $landingPage
     * @param string $keyword
     * @param string $typeTag
     * @param string $market
     * 
     * @return string
     */
    private function makeYahooFeedWebsiteUrl(string $mainSite, string $landingPage, string $keyword, string $typeTag, string $market): string
    {
        return 'https://' . strtolower($market) . '.' . $mainSite . '/search/4/?type='.$typeTag . 
        '&keyword=' . $this->formatKeyword($keyword, '+') . '&source=facebook';
    }


    /**
     * @param string $marketId
     * 
     * @return array
     */
    public function generateNewBodyTexts(string $marketId, string $keyword)
    {
        $marketService = new AdTextService;
       
        $adTexts = $marketService->getRandomDataByMarketId($marketId);
        if (count($adTexts) > 0) {
            foreach ($adTexts as $key => $adText) {
                $adTexts[$key]->title1 = $this->replaceKeywordPlaceHolderInText($adText->title1, $keyword);
                $adTexts[$key]->title2 = $this->replaceKeywordPlaceHolderInText($adText->title2, $keyword);
                $adTexts[$key]->body1 = $this->replaceKeywordPlaceHolderInText($adText->body1, $keyword);
                $adTexts[$key]->body2 = $this->replaceKeywordPlaceHolderInText($adText->body2, $keyword);
            }
        } 
        return $adTexts;
    }

    /**
     * @param string $text
     * @param mixed $keyword
     * 
     * @return string
     */
    public function replaceKeywordPlaceHolderInText(string $text, $keyword): string
    {

        $startPos = strpos($text, '{'); $endPos = strpos($text, '}');
        if ($startPos !== false && $endPos !== false) {
            $startPos++;
            $placeholder = substr($text, $startPos, $endPos - $startPos);
            $sm = new StringManipulator;
            $newText = preg_replace("~".preg_quote('{')."(.*?)".preg_quote('}')."~", 
                $sm->isCapsLock($placeholder) ? strtoupper($keyword) : ucfirst($keyword), $text);
            return $newText;
        }
        return $text;
    }   

}