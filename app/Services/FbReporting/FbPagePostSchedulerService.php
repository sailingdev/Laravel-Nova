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

    /**
     * @param array $data
     * @param mixed $rowId
     * 
     * @return array
     */
    public function updateSchedule(array $data, $rowId): array
    {
        if (FbPagePostScheduler::where('id', $rowId)->update($data)) {
            return [true, FbPagePostScheduler::where('id', $rowId)->first()];
        }
        return [false];
    }

    /**
     * @param mixed $rowId
     * 
     * @return bool
     */
    public function deleteRow($rowId): bool
    {
       if(FbPagePostScheduler::where('id', $rowId)->delete()) {
           return true;
       }
       return false;
    }
}