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
        $feeds = ['iac']; // 'yahoo', 'media', 

        foreach ($keywords as $keyword) {
            foreach($feeds as $feed) {
                array_push($data, [
                    'batch_id' => $batchId,
                    'keyword' => $keyword,
                    'market' => $market,
                    'feed' => $feed,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
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
                ->select('keyword', 'status', 'feed', 'market', 'created_at')
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
        
        $campaignCombo = $this->loadCampaigns([$this->facebookCampaign->getAccount3Id(), $this->facebookCampaign->getAccount21Id(), 
            $this->facebookCampaign->getAccountRD1Id()]);
     
        $acs = new AdAccountService;

        foreach ($submittedKeywords as $submission) {
        
            $countKeyword = $this->rpcService->countKeyword($submission['keyword'], $submission['market'], $submission['feed']);
            
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
                   
                    $adAccount = $acs->determineTargetAccountByFeed($submission['feed']);
                    if ($adAccount == null) {
                        Log::info('No target was found while processPendingBatchesUsingTypeTags ::: ' . $submission['feed'], [$submission]);
                    }
                    else {
                        $process = $this->duplicateCampaign(current($matches), $submission, $adAccount);
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
    public function duplicateCampaign($campaign, $submission, $targetAccount, $bidStrategy=null, $batchId=null)
    {  
       
        // only run if market is in the list of supported markets
        $adAccountService = new AdAccountService;
        $websiteService = new WebsiteService;
        $cdService = new CampaignDuplicateService;
        $row = $adAccountService->getRowByAccountId(preg_replace("#[^0-9]#i", "", $targetAccount));
        $domain = $this->facebookCampaign->getSiteFromAdAccountConfigurations($row->configurations);
        $websiteData = $websiteService->getRowByDomain($domain);
        $sm = new StringManipulator;

        if (strtolower($submission['feed']) == 'yahoo') { 
            // update main to completed
            // $cdService->updateMainRow($batchId);
        }

        if ($websiteData['supported_markets'] != null) {
            
            $supportedMarkets =  strtolower($submission['feed']) == 'media' ? ['US', 'CA'] : 
                $sm->generateArrayFromString($websiteData['supported_markets'], ',');
             
            if (in_array($submission['market'], $supportedMarkets) || strtolower($submission['feed']) == 'iac') {
                $campaignNameExtracts = $this->facebookCampaign->extractDataFromCampaignName($campaign['name']);

                $loggedErrors = [];

                $adsetsToRollBack = $adsToRollBack = $adCreativesToRollBack = $campaignsToTrack = []; 

                $marketService = new MarketService;
                $marketCode = $marketService->getMarketIdbyCode($submission['market']);
                
                Log::info('Reporting for account', [$targetAccount]);
            
                $typeTag = isset($submission->type_tag) ? $submission->type_tag : 
                $this->facebookCampaign->generateTypeTag($submission['keyword'], $submission['market'], 'related');

                $newCampaignName = $this->facebookCampaign->formatCampaignName(
                    $submission['keyword'],
                    $submission['market'],
                    $websiteData->feed,
                    $domain,
                    $typeTag   
                );
               
                $newCampaignData = [
                    'name' => $newCampaignName,
                    'objective' => $campaign['objective'],
                    'bid_strategy' =>  $bidStrategy === null ? 'COST_CAP' : $bidStrategy, 
                    'buying_type' => $campaign['buying_type'],
                    'daily_budget' => 500,
                    'status' => $this->facebookCampaign->determineStatus($campaign['status']),
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
                
                $marketsArr[] = $submission['market'] == 'UK' ? 'GB' : $submission['market'];
                if (strtolower($submission['feed']) == 'iac') {
                    $devicePlatforms = ['mobile'];
                }
                else if (strtolower($submission['feed']) == 'media') {
                    $devicePlatforms = ['mobile'];
                }
                else if (strtolower($submission['feed']) == 'yahoo') {
                    $devicePlatforms = ['desktop'];
                }

          
                foreach ($existingCampaignAdsets[1] as $existingAdSet) {      
                    // dd($existingAdSet);
                    $newAdsetTargeting = $existingAdSet->targeting;
                    $newAdsetTargeting['geo_locations']['countries'] = $marketsArr;
                    $newAdsetTargeting['device_platforms'] = $devicePlatforms;
    
                    $sm = new StringManipulator;
        
                    $newAdsetTargeting['locales'] = $sm->generateArrayFromString(
                        $this->facebookAdLocale->getMarketLocale($marketCode), ',');
        
                    $targetAccountData = $this->facebookAdAccount->loadAccount($targetAccount, [
                            'timezone_name'
                    ]); 
                    
                    $accountTimezone = $targetAccountData[0] === true ? $targetAccountData[1]->timezone_name : "UTC";
                    
                    $newBidAmount = $this->rpcService->averageRpcOfMarketInLast7Days($submission['market'], $submission['feed']);
                    
                   
                    if (strtolower($submission['feed']) == 'iac') {
                        $promotedObject = [
                            'pixel_id' => '652384435238728',
                            'custom_event_type' => 'LEAD'
                        ];
                    } 
                    else {
                        // $promotedObject = $existingAdSet->promoted_object;
                        $promotedObject = [
                            'pixel_id' => '238715230770371',
                            'custom_event_type' => 'CONTENT_VIEW'
                        ];
                    }

                    $newAdsetData = [
                        'name' =>   ucfirst($submission['keyword']), 
                        'targeting' => $newAdsetTargeting,
                        'bid_amount' =>  $newBidAmount * 100,
                        'billing_event' => $existingAdSet->billing_event,
                        'promoted_object' => $promotedObject,
                        'start_time' => $this->facebookAdset->determineStartTime($accountTimezone),
                        'campaign_id' => $newCampaign[1]['id'],
                        'is_dynamic_creative' => true
                    ];
                    // create new adset
                    $newAdSet = $this->facebookAdset->create($targetAccount, $newAdsetData);
                    
                    if ($newAdSet[0] == false) {
                        
                        if ($newAdSet[1]->getErrorUserTitle() == 'No Custom Audience Ownership') {
                            continue;
                        }
                        else {
                            array_push($loggedErrors, [
                                'message' => 'An adset not created',
                                'errors' => $newAdSet[1],
                                'data' => $newAdsetData
                            ]); 
                            
                            // delete the newly created campaign
                            $this->facebookCampaign->delete($newCampaign[1]['id']);
                        }
                    }
                    else {
                        Log::info('Adset for '. $targetAccount . ' created', [$newAdSet[1]->id]);
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
                                        $submission['market'],
                                        $newCampaignName
                                    ); 
                                    // dd($newWebsiteUrl);
                                    $existingAdSetFeedSpec['link_urls'][0]['website_url'] = $newWebsiteUrl;
                                
                                
                                    $newBodyTexts = $this->facebookCampaign->generateNewBodyTexts($marketCode, $submission['keyword']);
                                    
                                    
                                    if (count($newBodyTexts) > 1) {
                                        $rand = rand(1,2);
                                        
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
                                        Log::info('Adcreative for '. $targetAccount . ' created', [ $newAdCreative[1]->id]);
                                        $newAdData = [
                                            'name' => $existingAd->name,
                                            'adset_id' => $newAdSet[1]->id,
                                            'creative' => $this->facebookAdCreative->show($newAdCreative[1]->creative_id)[1],
                                            'status' => $this->facebookCampaign->determineStatus($existingAd->status),
                                            'conversion_domain' => $domain
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
                                            Log::info('Ad for '. $targetAccount . ' created', [$newAd[1]->id]);
                                            array_push($adsToRollBack, $newAd[1]->id);
                                            if (!array_key_exists($newCampaign[1]['id'], $campaignsToTrack)) {
                                                $campaignsToTrack[$newCampaign[1]['id']] = [
                                                    'type_tag' => $typeTag,
                                                    'campaign_start' => $this->facebookCampaign->determineStartTime(),
                                                    'feed' => $websiteData->feed
                                                ];
                                            }
                                        }
                                    } 
                                }
                            }
                        }
                    }
                } 
            
                if (count($campaignsToTrack) > 0) {
                    $cotService = new CampaignOptimizeTrackerService;
                    
                    foreach ($campaignsToTrack as $key => $data) {
                        // store the record for optimizer
                        $cotService->create([
                            'type_tag' => $data['type_tag'],
                            'feed' => $data['feed'],
                            'campaign_id' => $key,
                            'campaign_start' => $data['campaign_start']
                        ]);
                        
                        if ((string) $data['feed'] == 'iac') {
                            $cdService->create([
                                'batch_id' => $batchId == null ? Str::uuid() : $batchId, 
                                'type_tag' => $data['type_tag'],
                                'feed' => 'iac',
                                'campaign_id' => $key,
                                'type' =>  'main',
                                'main_batch_status' => 'uncompleted',
                                'campaign_start' => $data['campaign_start']
                            ]);
                        }
                       
                    }
                }

                if (count($loggedErrors) > 0) {
                    // log output
                    Log::info('An error occured WITH ONE OF THE CREATIONS', [$loggedErrors]);
                    
                    $this->rollBacks($newCampaign[1]['id'], $adsetsToRollBack, $adCreativesToRollBack, $adsToRollBack);
                    return [false, 'Process was not completed. Please check the log for the affected processes'];
                }
                else {
                    Log::info('Everything worked fine', []);
                    return [true, $newCampaign[1]['id']];
                }
            }
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
    public function loadToBeCreated()
    {
        $batches =  SubmittedKeyword::select(['id', 'batch_id', 'market', 'keyword', 'feed', 'status'])->where('status', 'pending')
            ->where('action_taken', 'new')->where('feed', 'iac')->get();
        return $batches;
        
    }

    /**
     * @return array
     */
    public function loadBatchHistory()
    {  
        return  SubmittedKeyword::select('*')
            ->where('action_taken', 'new')
            ->where('status', '!=', 'pending')->limit(10)
            ->orderBy('updated_at', 'desc')
            ->get(); 
    }

    /**
     * @param array $batches
     * 
     * @return bool
     */
    public function createCampaignFromRelated(array $keyword)
    { 
       
        $campaignCombo = $this->loadCampaigns([$this->facebookCampaign->getAccount3Id(), $this->facebookCampaign->getAccount21Id(), 
            $this->facebookCampaign->getAccountRD1Id()]);
        
        $wbs = new WebsiteService;
        $acs = new AdAccountService;
     
        
        $matches = array_filter($campaignCombo, function ($campaign) use ($keyword) {
            $campaignTypeTag = $this->facebookCampaign->extractDataFromCampaignName($campaign['name'])['type_tag'];
            return $campaignTypeTag == trim($keyword['type_tag']);
        });
         
        if (count($matches) > 0) {
            
            $typeTag = $this->facebookCampaign->generateTypeTag($keyword['keyword'], $keyword['market'], 'related');
            
            $keyword['type_tag'] = $typeTag;
            
            // using the feed, determine the ad account to duplicate into
            $adAccount = $acs->determineTargetAccountByFeed($keyword['feed']);
           
            if ($adAccount == null) {
                Log::info('No target was found for createCampaignFromRelated ::: ' . $keyword['feed'], [$keyword]);
            }
            else {
                
                $process = $this->duplicateCampaign(current($matches), $keyword, $adAccount);
         
                if ($process[0] == true) {
                    $this->updateRow($keyword['batch_id'], $keyword['keyword'], [
                        'status' => 'processed'
                    ]);
                } 
                else {
                    $this->updateRow($keyword['batch_id'], $keyword['keyword'], [
                        'status' => 'pending'
                    ]);
                }
            }
               
        }
        else {
            $this->updateRow($keyword['batch_id'], $keyword['keyword'], [
                'status' => 'pending'
            ]);
        }
        
        return true;
    }

    /**
     * @param string $batchId
     * 
     * @return
     */
    public function getToBeCreatedCampaignsByBatchIdAndKeyword(string $batchId, string $keyword)
    {
        return SubmittedKeyword::where('batch_id', $batchId)
        ->where('keyword', $keyword)
        ->where('action_taken', 'new')
        ->where('status', '!=', 'processed')
        ->get();
    
    }

    public function deleteKeyword(string $id): bool
    {
        if (SubmittedKeyword::where('id', $id)->delete()) {
            return true;
        }
        return false;
    }


    public function createCampaignFromTemplate(array $submittedKeywords, string $market)
    {  
        $campaignId = '23846614980830456';
        $facebookCampaign = new FacebookCampaign;
        $template = $facebookCampaign->show($campaignId, [
            'name', 
            'status', 
            'objective', 
            'bid_strategy',
            'buying_type',
            'daily_budget',
            'special_ad_categories',
            'account_id'
        ]); 

        if ($template[0] !== false) {
                     
            $campaign = [
                'id' => $template[1]->id,
                'name' => $template[1]->name,
                'status' => $template[1]->status,
                'objective' => $template[1]->objective,
                'bid_strategy' => $template[1]->bid_strategy,
                'buying_type' => $template[1]->buying_type,
                'daily_budget' => $template[1]->daily_budget,
                'special_ad_categories' => $template[1]->special_ad_categories,
                'account_id' =>  $template[1]->account_id
            ];
            $acs = new AdAccountService;

            $feed = 'iac';
            $adAccount = $acs->determineTargetAccountByFeed($feed);

            foreach ($submittedKeywords as $submittedKeyword) {
                $submission = [
                    'feed' => $feed,
                    'keyword' => $submittedKeyword,
                    'market' => $market,
                    'type_tag' => $facebookCampaign->generateTypeTag($submittedKeyword, $market, 'related')
                ]; 
                $this->duplicateCampaign($campaign, $submission, $adAccount);
            }
            
        }
    }
}