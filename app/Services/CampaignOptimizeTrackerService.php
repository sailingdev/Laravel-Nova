<?php

namespace App\Services;

use App\Models\FbReporting\CampaignOptimizeTracker;
use App\Revenuedriver\FacebookCampaign;
use App\Services\Tools\ToolsExecutionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignOptimizeTrackerService
{
   /**
    * @param array $data
    * 
    * @return bool
    */
   public function create(array $data): bool
   {
      DB::beginTransaction();
      
      $cot = new CampaignOptimizeTracker;
      $cot->type_tag = $data['type_tag'];
      $cot->campaign_id = $data['campaign_id'];
      $cot->feed = $data['feed'];
      $cot->campaign_start = $data['campaign_start'];
      $cot->save();

      DB::commit();
      return true;
   }

   
   public function getAll()
   {
      return CampaignOptimizeTracker::all();
   }
   
   public function optimizer()
   {
      
   }

   public function OptimizeDay1()
   {
      DB::setDefaultConnection('mysql_tools');
      $tes = new ToolsExecutionService;
      if ($tes->hasRunDailyReportGeneratorMSS()) {
         // select from type daily perf 
         DB::setDefaultConnection('mysql');
         $campaigns = $this->getAll();
        
         if (count($campaigns) > 0) {
            $typeDailyPerfService = new TypeDailyPerfService;
            foreach ($campaigns as $campaign) {
               $day1 = $campaign->campaign_start;
               $opt = $typeDailyPerfService->getCampaignToOptimize($campaign->feed, $campaign->type_tag, $day1);
                
               if ($opt !== null) {
                  if ($opt->tot_clicks < 10) {
                     // load the campaigns and increase the budget
                     $facebookCampaign = new FacebookCampaign;
                     $accountCampaigns = $facebookCampaign->show($campaign->campaign_id, [
                        'daily_budget'
                     ]);
                     if ($accountCampaigns[0] !== false) { 
                        $oldBudget = $accountCampaigns[1]->daily_budget;
                        $newBudget = (int) $oldBudget + 50;

                        $updateCampaign = $facebookCampaign->update($campaign->campaign_id, [], [
                           'daily_budget' => $newBudget
                        ]);
                        if ($updateCampaign[0] === false ) {
                           Log::info('Scheduler Error while running OptimizeDay1', [$updateCampaign[1]]);
                        } 
                     }
                     else {
                        Log::info('Scheduler Error while running OptimizeDay1 ::: Campaign not found', [$accountCampaigns[1]]);
                     }
                  }
               }
            }
         }
      }
   }
}