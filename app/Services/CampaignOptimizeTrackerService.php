<?php

namespace App\Services;

use App\Models\FbReporting\CampaignOptimizeTracker;
use App\Models\FbReporting\Rpc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
      $cot->save();

      DB::commit();
      return true;
   }
}