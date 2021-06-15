<?php

namespace App\Services;

use App\Models\FbReporting\CampaignDuplicate;
use App\Models\FbReporting\CampaignOptimizeTracker;
use App\Revenuedriver\FacebookCampaign;
use App\Services\Tools\ToolsExecutionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignDuplicateService
{
   /**
    * @param array $data
    * 
    * @return bool
    */
   public function create(array $data): bool
   {
      DB::beginTransaction();
      
      $cd = new CampaignDuplicate;
      $cd->batch_id = $data['batch_id'];
    
      $cd->type_tag = $data['type_tag'];
      $cd->campaign_id = $data['campaign_id'];
      $cd->feed = $data['feed'];
      $cd->campaign_start = $data['campaign_start'];
      $cd->type = $data['type'];
      $cd->main_batch_status = $data['main_batch_status'];
      
      $cd->save();

      DB::commit();
      return true;
   }

   
   public function getAll()
   {
      return CampaignDuplicate::all();
   }

   public function getAllUncompletedBatches()
   {
        return CampaignDuplicate::where('type', 'main')
        ->where('feed', 'iac')
        ->where('main_batch_status', 'uncompleted')
        ->where('campaign_start', '<=', Carbon::now())
        ->get();
   }

   public function getByFeedAndBatchId(string $feed, string $batchId)
   {
      return CampaignDuplicate::where('feed', $feed)->where('batch_id', $batchId)->first();
   }

    public function updateMainRow($batchId) 
    {
        $campaign = CampaignDuplicate::where('batch_id', $batchId)->where('type', 'main')->first();
       
        $campaign->main_batch_status = 'completed';
        $save = $campaign->save();
        return true;
    }
   
    public function runCampaignDuplicator()
    {
        $uncompletedBatches = $this->getAllUncompletedBatches();
        $sks = new SubmittedKeywordService;
        $acs = new AdAccountService;
        $facebookCampaign = new FacebookCampaign;
        $feed = $adAccount = null;

        
        if (count($uncompletedBatches) > 0) {
            foreach ($uncompletedBatches as $uncompletedBatch) {
                
                $facebookCampaign->initTT();
                // load
                $iacCampaign = $facebookCampaign->show($uncompletedBatch->campaign_id, [
                    'name', 
                    'status', 
                    'objective', 
                    'bid_strategy',
                    'buying_type',
                    'daily_budget',
                    'special_ad_categories',
                    'account_id'
                ]); 
                if ($iacCampaign[0] !== false) {
                    
                    $campaignNameExtracts = $facebookCampaign->extractDataFromCampaignName($iacCampaign[1]->name);
                    $campaign = [
                        'id' => $iacCampaign[1]->id,
                        'name' => $iacCampaign[1]->name,
                        'status' => $iacCampaign[1]->status,
                        'objective' => $iacCampaign[1]->objective,
                        'bid_strategy' => $iacCampaign[1]->bid_strategy,
                        'buying_type' => $iacCampaign[1]->buying_type,
                        'daily_budget' => $iacCampaign[1]->daily_budget,
                        'special_ad_categories' => $iacCampaign[1]->special_ad_categories,
                        'account_id' =>  $iacCampaign[1]->account_id
                    ];
                    
                    $feedOnQueue = ['iac', 'media', 'yahoo'];
                    foreach ($feedOnQueue as $fq) {
                        $feed = $fq;
                        $adAccount = $acs->determineTargetAccountByFeed($fq);

                        $row = $acs->getRowByAccountId(preg_replace("#[^0-9]#i", "", $adAccount));
                        $submission = [
                            'feed' => $feed,
                            'keyword' => $campaignNameExtracts['keyword'],
                            'market' => $campaignNameExtracts['market'],
                            'type_tag' => $facebookCampaign->generateTypeTag($campaignNameExtracts['keyword'], $campaignNameExtracts['market'], 'related')
                        ];
                        Log::info('See this', [$feed, $iacCampaign[1]->name]);
                         
                        $sks->duplicateCampaign($campaign, $submission, $adAccount, null, $uncompletedBatch->batch_id, 'tt', $row->environment, false);
                    }

                       
                }
                
            }
        }
    }

}