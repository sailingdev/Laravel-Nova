<?php

namespace App\Revenuedriver\Base;

use App\Labs\StringManipulator;
use App\Services\AdAccountService;
use App\Services\AdTextService;
use App\Services\MarketService;
use App\Services\WebsiteService;
use Carbon\Carbon;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

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
    protected $account38 = 'act_2877564995847585';

    /**
     * @var string
     */
    protected $account39 = 'act_265327981656779';

    /**
     * @var string
    */
    protected $account30 = 'act_426162348442901';

    /**
     * @var string
    */
    protected $accountRD1 = 'act_230931148668537';

    /**
     * @var string
    */
    protected $accountRD22 = 'act_268825541368920'; 

    /**
     * @var string
    */
    protected $accountRD26 = 'act_195606425356330'; // test tt iac

    protected $accountRD27 = 'act_167517138529238'; // prod tt iac

    protected $accountRD17 = 'act_4180769961935190'; // prod tt media

    protected $account57 = 'act_750037932347728'; // prod rd media 

    protected $account12 = 'act_351184159509129'; // prod rd yahoo

    protected $accountRD28 = 'act_3945312498910176'; //prod landing page for campaigns


    public function __construct()
    {
        $this->appId =  config('facebook.marketing.app_id');
        $this->appSecret = config('facebook.marketing.app_secret');
        $this->appAccessToken = config('facebook.marketing.rd_app_access_token');
       
        // Api::init($this->appId, $this->appSecret, $this->appAccessToken, false);
        
        // // The Api object is now available through singleton
        // $this->api = Api::instance(); 
        // $this->api->setLogger(new CurlLogger());
    }

    
    public function initRD()
    {
        Api::init(config('facebook.marketing.app_id'), config('facebook.marketing.app_secret'), 
        config('facebook.marketing.rd_app_access_token'), false);

        // The Api object is now available through singleton
        $this->api = Api::instance(); 
        $this->api->setLogger(new CurlLogger());
    }

    public function initTT()
    {
        Api::init(config('facebook.marketing.app_id'), config('facebook.marketing.app_secret'), 
        config('facebook.marketing.tt_app_access_token'), false);

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
            $data['keyword'] = array_key_exists(2, $extracts) ?  $this->formatKeyword(strtolower($extracts[2]), " ") : '';
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
        $start = $accountTimezone === "UTC" ? Carbon::parse('tomorrow') : 
            Carbon::parse('tomorrow', 'America/Los_Angeles');
           
        return $accountTimezone === "UTC" ? $start->toDateString(): 
            Carbon::parse($start)->setTimezone('UTC')->toDateString();
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
        $name = $this->formatKeyword(strtolower($keyword), '_') . " - " .
            strtoupper($market) . 
            " (".ucfirst(strtolower($feed)).")" . 
            " {" .strtolower($site) . "," .$typeTag . "," .$this->formatKeyword(strtolower($keyword), '+') . "," . strtoupper($market) ."}";
        
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
        // remove accent
        $keyword = str_replace("'", '', iconv('UTF-8', 'ascii//TRANSLIT//IGNORE', $keyword));
        $keywordPrep =  strtolower(str_replace(' ', '_', trim($keyword))); 

        // $keywordPrep =  $this->formatKeyword($keyword, '_', true);

        if ($createType === "related") {
            $typeTag =  $keywordPrep. '_' 
            . strtoupper($market) . '_' .
            // $datePrep . '_' .
            'cp' . '_' . 
            '009';
        }
        else if ($createType === "template") {
            $typeTag = $keywordPrep . '_' 
            . strtoupper($market) . '_' .
           'cp_' . 
            '009';
        }
        else {
            $typeTag = $keywordPrep . '_' . 
            strtoupper($market) . '_' .
            strtoupper($siteTag) . '_' .
            strtoupper($dupNo) . '_' . 
            'cp_' . 
            '009';
        } 
        return $typeTag;
    }

    /**
     * @return array
     */
    public function getTargetAccounts(): array
    {    return [$this->account57, $this->account12, $this->accountRD27];
        if (config('app.env') === 'production') {
            return [$this->account57, $this->account12, $this->accountRD27];
        }
        return [$this->account30, $this->account38, $this->accountRD26];
    }

    /**
     * @param string $status
     * 
     * @return string
     */
    public function determineStatus(string $status, string $targetAccount): string
    { 
        // return 'PAUSED';
        if (config('app.env') === 'production' && $targetAccount != $this->accountRD28) {
            return 'ACTIVE';
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
     * @return string
     */
    public function getAccountRD1Id(): string
    {
        return $this->accountRD1;
    }

    /**
     * @return string
     */
    public function getAccountRD26(): string
    {
        return $this->accountRD26;
    }

    /**
     * @return string
     */
    public function getAccountRD27(): string
    {
        return $this->accountRD27;
    }

    /**
     * @return string
     */
    public function getAccountRD28(): string
    {
        return $this->accountRD28;
    }
    


    /**
     * @param string $keyword
     * 
     * @return string
     */
    public function formatKeyword(string $keyword, string $replacer='_', bool $removeSpecialChars=false): string
    {
        $keyword = str_replace(' ', $replacer, trim($keyword)); 
        return $keyword; 
    }

  
    /**
     * @param string $accountId
     * @param string $keyword
     * @param string $typeTag
     * @param string $market
     * @param string $campaignName
     * 
     * @return mixed
     */
    public function generateAdCreativeWebsiteUrl(string $accountId, string $keyword, string $typeTag, string $market, string $campaignName)
    {
       
        $adAccountService = new AdAccountService;
        $row = $adAccountService->getRowByAccountId($accountId);
       
        if ($row != null) {
            $domain = $this->getSiteFromAdAccountConfigurations($row->configurations);
           
            if ($domain != null) {
                 
                $websiteService = new WebsiteService; 
                $domainData = $websiteService->getRowByDomain($domain);
              
                if ($domainData != null) {
                   
                    // check if in supported markets
                    $sm = new StringManipulator;
                  
                    if ($domainData['supported_markets'] != null) {
                        $supportedMarkets = $sm->generateArrayFromString($domainData['supported_markets'], ',');
                          
                        
                        if ($domainData['feed'] == 'media') {
                            return $this->makeMediaFeedWebsiteUrl($domain, $domainData['subdomain'], $keyword, $typeTag, $market);
                        }
                        else if ($domainData['feed'] == 'yahoo') {
                            return $this->makeYahooFeedWebsiteUrl($domain, $keyword, $typeTag, $market);
                        }
                        else if ($domainData['feed'] == 'iac') {
                            return $this->makeIacFeedWebsiteUrl($domain, $keyword, $typeTag, $market, $domainData['range_id'], $campaignName);
                        }
                        else if ($domainData['feed'] == 'cbs') {
                            return $this->makeCbsFeedWebsiteUrl($domain, $keyword, $typeTag, $market, $domainData['range_id']);
                        }
                    }

                } 
            }
           
        }
        return null;      
    }
 
    /**
     * @param string $domain
     * @param string $subdomain
     * @param string $keyword
     * @param string $typeTag
     * @param string $market
     * 
     * @return string
     */
    private function makeMediaFeedWebsiteUrl(string $domain, string $subdomain, string $keyword, string $typeTag, string $market): string
    {
        $sm = new StringManipulator; 
        return 'https://' . $subdomain . '.' . $domain . '/search/?q=' . $this->formatKeyword(ucfirst($keyword), '+') . 
        '&p=5&chnm=facebook&chnm2=fb_' .$sm->generateArrayFromString($domain, '.')[0] . 
        '_' . strtolower($market) . '&chnm3=' . $typeTag;
    }

    /**
     * @param string $domain 
     * @param string $keyword
     * @param string $typeTag
     * @param string $market
     * 
     * @return string
     */
    private function makeYahooFeedWebsiteUrl(string $domain, string $keyword, string $typeTag, string $market): string
    { 
        return 'https://' . strtolower($market) . '.' . $domain . '/search/4?type='.$typeTag . 
        '&keyword=' . $this->formatKeyword(ucfirst($keyword), '+') . '&source=facebook';
    }

    /**
     * @param string $domain
     * @param string $keyword
     * @param string $typeTag
     * @param string $market
     * @param string $rangeId
     * @param string $campaignName
     * 
     * @return string|null
     */
    public function makeIacFeedWebsiteUrl(string $domain, string $keyword, string $typeTag, string $market, string $rangeId, string $campaignName): ?string
    {
        if ($domain === 'allresultsweb.com') {
            return $this->makeIacAllResultsWebWebsiteUrl($domain, $keyword, $typeTag, $market, $rangeId, $campaignName);
        }
        $groupA = ['US'];
        $unitSuffix = $domain === 'top10answers.com' ? '&adUnitId=366911' : '';

        if (in_array($market, $groupA)) {
            return 'https://search.'.$domain.'/ar?q=' . $this->formatKeyword(ucfirst($keyword), '+') . 
            '&src=3&campname=' . $typeTag . '&rangeId=' . $rangeId . $unitSuffix;
        }
        
        $groupB = ['CA', 'AU', 'UK', 'IE', 'IN', 'NZ'];
      
        if (in_array($market, $groupB)) { 
            $suf = $market == 'UK' ? 'GB' : $market;
            return 'https://search.' . $domain . '/ar?q=' . 
            $this->formatKeyword(ucfirst($keyword), '+') . '&src=3&campname=' .$typeTag. '&rangeId='.$rangeId.'&mkt=en-' . $suf . $unitSuffix;
        } 

        $groupC = ['DE','FR','ES','IT','NL','SE','NO','DK','BR'];
        if (in_array($market, $groupC)) { 
            return 'https://search.' . $market . '.' . $domain . '/ar?q=' . $this->formatKeyword(ucfirst($keyword), '+') .
            '&src=3&campname=' . $typeTag . '&rangeId=' . $rangeId . $unitSuffix;
        }

        $groupD = ['AT', 'CH'];
        if (in_array($market, $groupD)) { 
            return 'https://search.de.'.$domain.'/ar?q='.
            $this->formatKeyword(ucfirst($keyword), '+').'&src=3&campname='.$typeTag.'&rangeId='.$rangeId.'&mkt=de-' . $market . $unitSuffix;
        } 
        return null;
    }

    public function makeIacAllResultsWebWebsiteUrl(string $domain, string $keyword, string $typeTag, string $market, string $rangeId, string $campaignName)
    { 
        $keyword = $this->formatKeyword(ucfirst($keyword), '+');
        $rangeId = '263';
        if ($market == 'US') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId;
        }
        else if ($market == 'CA') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=en-CA';
        }
        else if ($market == 'UK') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=en-UK';
        }
        else if ($market == 'DE') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=de-DE';
        }
        else if ($market == 'FR') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=fr-FR';
        }
        else if ($market == 'IT') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=it-IT';
        }
        else if ($market == 'ES') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=es-ES';
        }
        else if ($market == 'MX') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=es-MX';
        }
        else if ($market == 'AU') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=en-AU';
        }
        else if ($market == 'IE') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=en-IE';
        }
        else if ($market == 'IN') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=en-IN';
        }
        else if ($market == 'NL') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=nl-NL';
        }
        else if ($market == 'SE') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=sv-SE';
        }
        else if ($market == 'NO') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=nb-NO';
        }
        else if ($market == 'DK') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=da-DK';
        }
        else if ($market == 'BR') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=pt-BR';
        }
        else if ($market == 'AT') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=de-AT';
        }
        else if ($market == 'CH') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=de-CH';
        }
        else if ($market == 'NZ') {
            return 'https://top.allresultsweb.com/ar?src=44&q='.$keyword.'&campname='.$typeTag.'&rangeId=' . $rangeId . '&mkt=en-NZ';
        }
        return true;
    }

    
    /**
     * @param string $domain
     * @param string $keyword
     * @param string $typeTag
     * @param string $market
     * @param string $rangeId
     * 
     * @return string
     */
    private function makeCbsFeedWebsiteUrl(string $domain, string $keyword, string $typeTag, string $market, string $rangeId): string
    {
        return 'https://' . $domain . '/'.$market.'/seek?src=3&q=' .
            $this->formatKeyword(ucfirst($keyword), '+').'&qsrc=0&campname='.$typeTag.'&rangeId=' . $rangeId;
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


    public function getSiteFromAdAccountConfigurations(string $config): ?string
    {
        if ($config != null) {
            $sm = new StringManipulator;
            $configArray = $sm->generateArrayFromString(str_replace("\n", '<br />',  $config), '<br />');
            $search = [];
            if (count($configArray) > 0) {
                foreach ($configArray as $key => $line) { 
                    
                    $lineParams = array_map(function ($param) {
                        return trim($param);
                    }, $sm->generateArrayFromString($line, '=')); 

                    if (in_array('site', $lineParams)) {
                        $search = $lineParams;
                    }
                }
            }
           if (count($search) > 0) {
            return $search[1];
           }
        }
        return null;
    }
}