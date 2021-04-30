<?php 

namespace App\Services\FbReporting;

use App\Models\FbReporting\FbPagePostScheduler;
use Carbon\Carbon;

class FbPagePostSchedulerService
{
    /**
     * @param array $data
     * 
     * @return array
     */
    public function createSchedule(array $data): array
    {
        $scheduler = new FbPagePostScheduler;
        $scheduler->fb_page_post_id = $data['fb_page_post_id'];
        $scheduler->start_date = $data['start_date'];
        $scheduler->page_groups = json_encode($data['page_groups']);
        if ($scheduler->save()) {
            return [true, $scheduler];
        }
        return [false];
    }

    public function getAllScheduled()
    {
        return FbPagePostScheduler::where('start_date', '>=', Carbon::now())->get();        
    }
}