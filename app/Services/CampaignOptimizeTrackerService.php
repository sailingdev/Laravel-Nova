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
      DB::setDefaultConnection('mysql_tools');
      $tes = new ToolsExecutionService;
      if ($tes->hasRunDailyReportGeneratorMSS()) {
         DB::setDefaultConnection('mysql');
         $this->OptimizeDay1();
         // $this->OptimizeDay2();
         // $this->OptimizeDay3();
         // $this->clearFromTracker();
      }
   }

   protected function OptimizeDay1()
   {
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

   protected function OptimizeDay2()
   {
      $campaigns = $this->getAll();
      
      if (count($campaigns) > 0) {
         $typeDailyPerfService = new TypeDailyPerfService;
         foreach ($campaigns as $campaign) {
            $day2 =  Carbon::parse($campaign->campaign_start)->addDays(1);
            $opt = $typeDailyPerfService->getCampaignToOptimize($campaign->feed, $campaign->type_tag, $day2);
               
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
                        Log::info('Scheduler Error while running OptimizeDay2', [$updateCampaign[1]]);
                     } 
                  }
                  else {
                     Log::info('Scheduler Error while running OptimizeDay2 ::: Campaign not found', [$accountCampaigns[1]]);
                  }
               }
            }
         }
      }
   }

   /**
    * @return [type]
    */
   protected function OptimizeDay3ConsecutiveDays()
   {
      $campaigns = $this->getAll();
      
      if (count($campaigns) > 0) {
         $typeDailyPerfService = new TypeDailyPerfService;
         foreach ($campaigns as $campaign) {
            $day1 =  $campaign->campaign_start;

            $opt = $typeDailyPerfService->getCampaignToOptimize($campaign->feed, $campaign->type_tag, $day1);
               
            if ($opt !== null) {
            
               if ($opt->tot_clicks < 10) {

                  // check clicks for day 2
                  $day2 =  Carbon::parse($campaign->campaign_start)->addDays(1);
                  $opt2 = $typeDailyPerfService->getCampaignToOptimize($campaign->feed, $campaign->type_tag, $day2);

                  if ($opt2 !== null && $opt2->tot_clicks <  10) {
                     // check clicks for day 3
                     $day3 =  Carbon::parse($campaign->campaign_start)->addDays(2);
                     $opt3 = $typeDailyPerfService->getCampaignToOptimize($campaign->feed, $campaign->type_tag, $day3);
                     if ($opt3 !== null && $opt3->tot_clicks < 10) {
                         // load the campaigns and increase the budget
                        $facebookCampaign = new FacebookCampaign;
                        $accountCampaigns = $facebookCampaign->show($campaign->campaign_id, [
                           'daily_budget', 'bid_strategy', 'name'
                        ]);

                        if ($accountCampaigns[0] !== false) { 
                           $campaignNameExtracts = $this->facebookCampaign->extractDataFromCampaignName($accountCampaigns[1]->name);
                           $submission = [
                              'feed' => $campaignNameExtracts['feed'],
                              'type_tag' => $campaignNameExtracts['type_tag'], // unsure if to use the same type tag
                              'keyword' => $campaignNameExtracts['keyword'],
                              'market' => $campaignNameExtracts['market'],
                           ];
                           $sks = new SubmittedKeywordService;
                           $sks->duplicateCampaign($accountCampaigns[1], $submission, 'LOWEST_COST_WITH_BID_CAP');
                        }
                        else {
                           Log::info('Scheduler Error while running OptimizeDay3 ::: Campaign not found', [$accountCampaigns[1]]);
                        }
                     }
                  }
               }
            }
         }
      }
   }

   /**
    * @return 
    */
   protected function clearFromTracker()
   {
      return CampaignOptimizeTracker::where('campaign_start', '<=', Carbon::now()->subDays(3)->toDateTimeString())
      ->delete();
   }
}