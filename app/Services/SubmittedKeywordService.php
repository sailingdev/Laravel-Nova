<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Labs\StringManipulator;
use App\Revenuedriver\FacebookAd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Revenuedriver\FacebookAdset;
use App\Revenuedriver\FacebookAdImage;
use App\Revenuedriver\FacebookCampaign;
use App\Revenuedriver\FacebookAdCreative;
use App\Revenuedriver\FacebookAdLocale;
use App\Revenuedriver\FacebookAdAccount;
use App\Models\FbReporting\SubmittedKeyword;
use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;

class SubmittedKeywordService
{
    
    /**
     * @var App\Services\RpcService;
     */
    protected $rpcService;

    /**
     * @var App\Revenuedriver\FacebookCampaign;
     */
    protected $facebookCampaign;

    /**
     * @var App\Revenuedriver\FacebookAdset;
     */
    protected $facebookAdset;

    /**
     * @var App\Revenuedriver\FacebookAd;
     */
    protected $facebookAd;

    /**
     * @var App\Revenuedriver\FacebookAdCreative;
     */
    protected $facebookAdCreative;

    /**
     * @var App\Revenuedriver\FacebookAdImage;
    */
    protected $facebookAdImage;

     /**
     * @var App\Revenuedriver\FacebookAdLocale;
    */
    protected $facebookAdLocale;

  
    /**
     * Service constructor
     *
     * @return void 
    */
    public function __construct()
    {
        $this->rpcService = new RpcService;
        $this->facebookCampaign = new FacebookCampaign;
        $this->facebookAdset = new FacebookAdset;
        $this->facebookAd = new FacebookAd;
        $this->facebookAdCreative = new FacebookAdCreative;
        $this->facebookAdImage = new FacebookAdImage;
        $this->facebookAdLocale = new FacebookAdLocale;
        $this->facebookAdAccount = new FacebookAdAccount;
    }

    public function submit(array $keywords, string $market)
    {
        $batchId = $this->createBatchId();
 
        $data = [];
        foreach ($keywords as $keyword) {
            array_push($data, [
                'batch_id' => $batchId,
                'keyword' => $keyword,
                'market' => $market,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        DB::beginTransaction();
        SubmittedKeyword::insert($data);
        DB::commit();

        return [true, $batchId, $data];
    }

    /**
     * @return array
     */
    public function loadKeywordBatches()
    {
        $batches = SubmittedKeyword::select('batch_id', 'created_at', 'market')->distinct()->latest()->limit(10)->get();
        $data = [];
        if (count($batches) > 0) {
            foreach ($batches as $batch) {
                $rel = new \stdClass;
                $rel->batch_id = $batch->batch_id;
                $rel->market = $batch->market;
                $batchKeywords = SubmittedKeyword::where('batch_id', $batch->batch_id)
                ->select('keyword', 'status', 'market', 'created_at')
                ->latest()
                ->get();
                $keywordsInProgress = 0;
                foreach ($batchKeywords as $batchKeyword) {
                    if ($batchKeyword->status == 'pending' || $batchKeyword->status == 'processing') {
                        $keywordsInProgress++;
                    }
                }
                $rel->batch_status = $keywordsInProgress > 0 ? 'processing' : 'processed';
                $rel->keywords = $batchKeywords;
                array_push($data, $rel);
            }
        } 
        return $data;
    }

    /**
     * Create batch ID
     *
     * @return string
     */
    protected function createBatchId(): string
    {
        return (string) Str::orderedUuid();
    }

    /**
     * @param mixed $submittedKeywords
     * 
     * @return mixed
     */
    public function processSubmittedKeywords($submittedKeywords)
    {  
        
        $campaignCombo = $this->loadCampaigns([$this->facebookCampaign->getAccount3Id(), $this->facebookCampaign->getAccount21Id()]);
        
        foreach ($submittedKeywords as $submission) {
            $countKeyword = $this->rpcService->countKeyword($submission['keyword'], $submission['market']);
            
            if ($countKeyword > 0) {
                $this->updateRow($submission['batch_id'], $submission['keyword'], [
                    'action_taken' => 'skipped',
                    'note' => 'campaign is already running',
                    'status' => 'processed'
                ]);
            } 
            else {
                $matches = array_filter($campaignCombo, function ($campaign) use ($submission) {
                    $campaignKeyword = $this->facebookCampaign->extractDataFromCampaignName($campaign['name'])['keyword'];
                    return $campaignKeyword == $submission["keyword"];
                });
             
                if (count($matches) < 1) {
                    $this->updateRow($submission['batch_id'], $submission['keyword'], [
                        'action_taken' => 'new',
                        'note' => 'campaign to be created',
                        'status' => 'pending'
                    ]);
                } 
                else { 
                  
                    $process = $this->duplicateCampaign(current($matches), $submission);
                    
                    if ($process[0] == true) {
                        $this->updateRow($submission['batch_id'], $submission['keyword'], [
                            'action_taken' => 'skipped',
                            'note' => 'campaign restarted',
                            'status' => 'processed'
                        ]);
                    }
                }
            }
        } 
        return [true, 'submitted'];
    }

    /**
     * Load out an array of campaigns
     *
     * @param array $accountIds
     * 
     * @return array
     */
    protected function loadCampaigns(array $accountIds): array
    {
        $campaignCombo = [];
        foreach ($accountIds as $account) {
            $accountCampaigns = $this->facebookCampaign->loadCampaign($account, [
                'name', 
                'status', 
                'objective', 
                'bid_strategy',
                'buying_type',
                'daily_budget',
                'special_ad_categories',
                'account_id'
            ]);
           
            if ($accountCampaigns[0] !== false) { 
                foreach ($accountCampaigns[1] as $campaign) {
                    array_push($campaignCombo, [
                        'name' => $campaign->name,
                        'status' => $campaign->status,
                        'id' => $campaign->id,
                        'objective' => $campaign->objective,
                        'bid_strategy' => $campaign->bid_strategy,
                        'buying_type' => $campaign->buying_type,
                        'daily_budget' => $campaign->daily_budget,
                        'special_ad_categories' => $campaign->special_ad_categories,
                        'account_id' => $account
                    ]);
                }
            }
        }
        return $campaignCombo;
    }

    /**
     * @param mixed $campaign
     * @param mixed $submission
     * 
     * @return array
     */
    protected function duplicateCampaign($campaign, $submission)
    {  
        
        $campaignNameExtracts = $this->facebookCampaign->extractDataFromCampaignName($campaign['name']);
        
        $loggedErrors = [];

        $adsetsToRollBack = $adsToRollBack = $adCreativesToRollBack = []; 

        $marketService = new MarketService;
        $marketCode = $marketService->getMarketIdbyCode($submission['market']);
        
        $targetAccounts = $this->facebookCampaign->getTargetAccounts();
        $adAccountService = new AdAccountService;
        
        $websiteService = new WebsiteService;
        foreach ($targetAccounts as $targetAccount) {
           
            $row = $adAccountService->getRowByAccountId(preg_replace("#[^0-9]#i", "", $targetAccount));
            $domain = $this->facebookCampaign->getSiteFromAdAccountConfigurations($row->configurations);
            
            $websiteData = $websiteService->getRowByDomain($domain);
            
            $newCampaignName = $this->facebookCampaign->formatCampaignName(
                $submission['keyword'],
                $submission['market'],
                $websiteData->feed,
                $domain,
                isset($submission->type_tag) ? $submission->type_tag : 
                $this->facebookCampaign->generateTypeTag($submission['keyword'], $submission['market'], 'related')    
            );
            
            $newCampaignData = [
                'name' => $newCampaignName,
                'objective' => $campaign['objective'],
                'bid_strategy' =>  'COST_CAP', 
                'buying_type' => $campaign['buying_type'],
                'daily_budget' => 500,
                'status' => $this->facebookCampaign->determineStatus($campaign['status']), //$campaign['status'],
                'special_ad_categories' => $campaign['special_ad_categories']
            ];

            //   // create the campaign
            $newCampaign = $this->facebookCampaign->createCampaign($targetAccount, $newCampaignData);
           

            if ($newCampaign[0] === false) {
                Log::info('A campaign was not created', [
                    'message' => $newCampaign[1]->getMessage(),
                    'errors' => $newCampaign[1],
                    'data' => $newCampaignData
                ]);
                return [false, 'Campaign not created: '.$newCampaign[1]->getMessage()];
            }
            
            $existingCampaignAdsets = $this->facebookCampaign->getAdsets($campaign['id']);
        
            if ($existingCampaignAdsets[0] === false) {
                Log::info('No existing campaign adsets were loaded', [
                    'message' => $existingCampaignAdsets[1]->getMessage(),
                    'errors' => $existingCampaignAdsets[1],
                    'data' => []
                ]);

                // delete the neewly created campaign
                $this->facebookCampaign->delete($newCampaign[1]['id']);
                
                return [false, 'No existing campaign adsets were loaded: '. $existingCampaignAdsets[1]->getMessage()];
            
            }
            else if ($existingCampaignAdsets[1]->count() < 1) {
                Log::info('No existing campaign adsets available', [
                    'message' => 'No existing campaign adsets available',
                    'errors' => [],
                    'data' => []
                ]);
                
                // delete the neewly created campaign
                $this->facebookCampaign->delete($newCampaign[1]['id']);

                return [false, 'No existing campaign adsets available'];
            }  

            foreach ($existingCampaignAdsets[1] as $existingAdSet) {      
            
                $newAdsetTargeting = $existingAdSet->targeting;
                $newAdsetTargeting['geo_locations']['countries'] = [$submission['market'] == 'UK' ? 'GB' : $submission['market']];
                
                $sm = new StringManipulator;
    
                $newAdsetTargeting['locales'] = $sm->generateArrayFromString(
                    $this->facebookAdLocale->getMarketLocale($marketCode), ',');
    
                $targetAccountData = $this->facebookAdAccount->loadAccount($targetAccount, [
                        'timezone_name'
                ]); 
                
                $accountTimezone = $targetAccountData[0] === true ? $targetAccountData[1]->timezone_name : "UTC";
                
                $newBidAmount = $this->rpcService->averageRpcOfMarketInLast7Days($submission['market'], $campaignNameExtracts['feed']);
                
                $newAdsetData = [
                    'name' =>   ucfirst($submission['keyword']), //$existingAdSet->name,
                    'targeting' => $newAdsetTargeting,
                    'bid_amount' => $newBidAmount > 0 ? $newBidAmount * 100 : 1,
                    'billing_event' => $existingAdSet->billing_event,
                    'promoted_object' => $existingAdSet->promoted_object,
                    'start_time' => $this->facebookAdset->determineStartTime($accountTimezone),
                    'campaign_id' => $newCampaign[1]['id'],
                    'is_dynamic_creative' => true
                ];
                // create new adset
                $newAdSet = $this->facebookAdset->create($targetAccount, $newAdsetData);
                
                if ($newAdSet[0] == false) {
                    array_push($loggedErrors, [
                        'message' => 'An adset not created',
                        'errors' => $newAdSet[1],
                        'data' => $newAdsetData
                    ]); 
                    
                    // delete the newly created campaign
                    $this->facebookCampaign->delete($newCampaign[1]['id']);
                }
                else {
                     
                    array_push($adsetsToRollBack, $newAdSet[1]->id);
    
                    // get ads for existing adset
                    $existingAds = $this->facebookAdset->getAds($existingAdSet->id);
                    if ($existingAds[0] == false) {
                        array_push($loggedErrors, [
                            'message' => 'An error occured while loading existing ads in adset with ID: ' . $existingAdSet->id,
                            'errors' => $existingAds[1],
                            'data' => []
                        ]); 
                    }
                    else if ($existingAds[1]->count() < 1) {
                        array_push($loggedErrors, [
                            'message' => 'No existing ads for the adset with ID: ' . $existingAdSet->id,
                            'errors' => [],
                            'data' => []
                        ]);
                    }
                    else {  
                        foreach ($existingAds[1] as $existingAd) {
                           
                            // load adcreative for that ad
                            $existingAdCreative = $this->facebookAdCreative->show($existingAd->creative['id'], [
                                'account_id', 'name', 'object_story_spec', 'asset_feed_spec', 'call_to_action_type',
                                'link_url', 'image_hash', 'image_url'
                            ]);
                           
                            if ($existingAdCreative[0] == false) {
                                array_push($loggedErrors, [
                                    'message' => 'An error occured while loading the adcreative for the ad with ID: ' . $existingAd->id,
                                    'errors' => $existingAdCreative[1],
                                    'data' => []
                                ]);
                            }
                            else {
                                // copy adcreative into new account  
                                
                               
    
                                $existingAdSetFeedSpec = $existingAdCreative[1]->exportAllData()['asset_feed_spec'];
                                $newAdImages = [];
                                foreach ($existingAdSetFeedSpec['images'] as $key => $existingImage) {
                                    // copy from old account to new account
                                    $copyFrom = new \stdclass;
                                    $copyFrom->source_account_id = preg_replace("#[^0-9]#i", "", $campaign['account_id']);
                                    $copyFrom->hash = $existingImage['hash'];
                                    $newAdImage = $this->facebookAdImage->create($targetAccount, [
                                        'copy_from' => $copyFrom
                                    ]);
                                    
                                    if ($newAdImage[0] == false) {
                                        array_push($loggedErrors, [
                                            'message' => 'An error occured while duplicating an image from source to target account. Existing ad Id: ' . $existingAd->id,
                                            'errors' => $newAdImage[1],
                                            'data' => (array) $copyFrom
                                        ]);
                                    }
                                    else {
                                        
                                        $arrK = array_keys($newAdImage[1]->exportAllData()['images']);
                                        array_push($newAdImages, [
                                            'hash' => $arrK[0]
                                        ]);
                                    }
                                }
    
                                $existingAdSetFeedSpec['images'] = $newAdImages;
                               
                                // generate new website url 
                                $newWebsiteUrl = $this->facebookCampaign->generateAdCreativeWebsiteUrl( 
                                    $targetAccount,
                                    ucfirst($submission['keyword']),
                                    isset($submission->type_tag) ? $submission->type_tag : $campaignNameExtracts['type_tag'],
                                    $submission['market'] 
                                ); 
                                 
                                $existingAdSetFeedSpec['link_urls'][0]['website_url'] = $newWebsiteUrl;
                              
                               
                                $newBodyTexts = $this->facebookCampaign->generateNewBodyTexts($marketCode, $submission['keyword']);
                                 
                                 
                                if (count($newBodyTexts) > 1) {
                                    $rand = rand(1,2);
                                    $randTitle = 'title'.$rand;
                                    $randBody = 'body'.$rand;
    
                                    $existingAdSetFeedSpec['titles'][0]['text'] = $newBodyTexts[0]->title1;
                                    $existingAdSetFeedSpec['bodies'][0]['text'] = $newBodyTexts[0]->body1;
    
                                    $existingAdSetFeedSpec['titles'][1]['text'] = $newBodyTexts[1]->title2;
                                    $existingAdSetFeedSpec['bodies'][1]['text'] = $newBodyTexts[1]->body2;
                                }
                                $fbPageService = new FbPageService;
                                $randomFbPage = $fbPageService->getRandomFbPage();
                                $objectStorySpec = [];

                                if ($randomFbPage != null) {
                                    $objectStorySpec = [
                                        'page_id' => $randomFbPage->page_id,
                                        'instagram_actor_id' => $randomFbPage->instagram_id
                                    ];
                                }
                                
                                $newAdCreativeData = [
                                    'name' =>  ucfirst($submission['keyword']),  
                                    'account_id' => $targetAccount,
                                    'asset_feed_spec' => $existingAdSetFeedSpec,
                                    'call_to_action_type' => $existingAdCreative[1]->call_to_action_type,
                                    'object_story_spec' => $objectStorySpec, 
                                ]; 
     
                                $newAdCreative = $this->facebookAdCreative->create($targetAccount, $newAdCreativeData);
                             
                                if ($newAdCreative[0] == false) {
                                    array_push($loggedErrors, [
                                        'message' => 'An error occured while duplicating adcreative from source into target account: Ad Id: '. $existingAd->id,
                                        'errors' => $newAdCreative[1],
                                        'data' => $newAdCreativeData
                                    ]);
                                }
                                else {
                                    array_push($adCreativesToRollBack, $newAdCreative[1]->id);
    
                                    $newAdData = [
                                        'name' => $existingAd->name,
                                        'adset_id' => $newAdSet[1]->id,
                                        'creative' => $this->facebookAdCreative->show($newAdCreative[1]->creative_id)[1],
                                        'status' => $this->facebookCampaign->determineStatus($existingAd->status)
                                    ];
                                    
                                    $newAd = $this->facebookAd->create($targetAccount, $newAdData);
                                   
                                    if ($newAd[0] == false) {
                                        array_push($loggedErrors, [
                                            'message' => 'An error occured while creating ad for the adset with ID: ' . $newAdSet[1]->id,
                                            'errors' => $newAd[1],
                                            'data' => $newAdData
                                        ]);
                                    }
                                    else { 
                                        array_push($adsToRollBack, $newAd[1]->id);
                                    }
                                } 
                            }
                        }
                    }
                }
            } 
        } 
     
        if (count($loggedErrors) > 0) {
            // log output
            Log::info('An error occured while processing some of the submitted keywords', $loggedErrors);
            
            $this->rollBacks($newCampaign[1]['id'], $adsetsToRollBack, $adCreativesToRollBack, $adsToRollBack);
            return [false, 'Process was not completed. Please check the log for the affected processes'];
        }
        else {
            return [true, 'Ad was successfully created'];
        }
    }


    /**
     * @param string $campaignId
     * @param array $adsets
     * @param array $adImages
     * @param array $adCreatives
     * @param array $ads
     * 
     * @return void
     */
    private function rollBacks(string $campaignId, array $adsets, array $adCreatives, array $ads): void
    {
        if ($campaignId !== '') {
            $this->facebookCampaign->delete($campaignId);
        }
        if (count($adsets) > 0) {
            foreach ($adsets as $adsetId) {
                $this->facebookAdset->delete($adsetId);
            }
        }  
        if (count($adCreatives) > 0) {
            foreach ($adCreatives as $adCreativeId) {
                $this->facebookAdCreative->delete($adCreativeId);
            }
        }
        if (count($ads) > 0) {
            foreach ($ads as $adId) {
                $this->facebookAd->delete($adId);
            }
        }
    }


    /**
     * @param string $batchId
     * @param string $keyword
     * @param array $data
     * 
     * @return \App\Models\FbReporting\SubmittedKeyword
     */
    public function updateRow(string $batchId, string $keyword, array $data)
    {
        return SubmittedKeyword::where('batch_id', $batchId)
        ->where('keyword', $keyword)
        ->update($data);
    }

   

    /**
     * @param mixed $summaryType=null
     * @param mixed $limit=null
     * 
     * @return array
     */
    public function loadBatchSummaries(): array
    {
        $batches =  SubmittedKeyword::select('batch_id', 'created_at', 'market')->where('status', 'pending')
            ->where('action_taken', 'new')->distinct()->latest()->get();
       
        $data = [];
        $sm = new StringManipulator;
        foreach($batches as $batch) {
            $rows =  SubmittedKeyword::where(function ($query) use ($batch) {
                return $query->where('batch_id', $batch->batch_id)
                ->where('status', 'pending');
            })
            ->orWhere(function ($query)  use ($batch) {
                return $query->where('batch_id', $batch->batch_id)
                ->where('status', 'processed')
                    ->where('action_taken', 'skipped'); 
            })
            ->get(); 
            
            $new = $skipped = [];
            $processingCount = 0;
            foreach($rows as $row) {
                if ($row->action_taken === 'new') {
                    array_push($new, [
                        'keyword' => strtolower($row->keyword),
                        'type_tag' => '',
                        'id' => $row->id,
                        'batch_id' => $row->batch_id
                    ]);
                }
                else {
                    array_push($skipped, $row->keyword);
                }

                if ($row->status == 'pending' || $row->status == 'processing') {
                    $processingCount++;
                }
            }
            $obj = new \stdClass;
            $obj->batch_id = $batch->batch_id;
            $obj->date = Carbon::parse($batch->created_at)->toDateString();
            $obj->market = $batch->market;
            
            $obj->skipped = $sm->generateStringFromArray($skipped, ',');
            $obj->to_create = $new;
            
            $obj->status = $processingCount > 0 ? 'processing' : 'processed';
            array_push($data, $obj);
        } 
       return $data;
    }

    /**
     * @return array
     */
    public function loadBatchHistory(): array
    { 
        $data = [];
        $sm = new StringManipulator;
      
        $rows = SubmittedKeyword::select('*')
            ->where('action_taken', 'new')
            ->where('status', '!=', 'pending')->limit(10)->orderBy('updated_at', 'desc')->get(); 
            
            foreach($rows as $row) {
                $new = $skipped = [];
                $processingCount = 0;
                if ($row->action_taken === 'new') {
                    array_push($new, [
                        'keyword' =>  $this->facebookCampaign->formatKeyword($row->keyword, '+'),
                        'type_tag' => '',
                        'id' => $row->id
                    ]);
                }
                else {
                    array_push($skipped, $row->keyword);
                }

                if ($row->status == 'pending' || $row->status == 'processing') {
                    $processingCount++;
                }

                $obj = new \stdClass;
                $obj->batch_id = $row->batch_id;
                $obj->date = Carbon::parse($row->created_at)->toDateString();
                
                $collec = [];
                foreach ($new as $keywordAssoc) {
                    array_push($collec, $keywordAssoc['keyword']);
                }
                $obj->to_create = $sm->generateStringFromArray($collec, ',');
                
                $obj->status = $processingCount > 0 ? 'processing' : 'processed';
                array_push($data, $obj);
            }   
        return $data;
    }

    /**
     * @param array $batches
     * 
     * @return bool
     */
    public function processPendingBatchesUsingTypeTags(array $keywords)
    {
        
        $campaignCombo = $this->loadCampaigns([$this->facebookCampaign->getAccount3Id(), $this->facebookCampaign->getAccount21Id()]);
       
        foreach ($keywords as $keyword) {
            $matches = array_filter($campaignCombo, function ($campaign) use ($keyword) {
                $campaignTypeTag = $this->facebookCampaign->extractDataFromCampaignName($campaign['name'])['type_tag'];
                return $campaignTypeTag == trim($keyword->type_tag);
            });
             
            if (count($matches) > 0) {
                // load the batch id of this keyword
                $submission = SubmittedKeyword::where('id', $keyword->id)->first();
                if ($submission !== null) {
                    $batchId = $submission->batch_id;
                    
                    $submission->type_tag = $this->facebookCampaign->generateTypeTag($submission->keyword, $submission->market, 'related');
                    $process = $this->duplicateCampaign(current($matches), $submission);
                    if ($process[0] == true) {
                        $this->updateRow($batchId, $submission->keyword, [
                            'status' => 'processed'
                        ]);
                    } 
                    else {
                        $this->updateRow($batchId, $submission->keyword, [
                            'status' => 'pending'
                        ]);
                    }
                }
              
            }
            else {
                $this->updateRow($keyword->batch_id, $keyword->keyword, [
                    'status' => 'pending'
                ]);
            }
        }
        return true;
    }
}