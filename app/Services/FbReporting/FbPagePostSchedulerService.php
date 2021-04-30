<?php 

namespace App\Services;

use App\Models\FbReporting\FbPagePostScheduler;

class FbPagePostSchedulerService
{
    public function createSchedule(array $data): array
    {
        $scheduler = new FbPagePostScheduler;
        $scheduler->fbPagePost->associate($data['fb_page_post']);
        $scheduler->start_date = $data['start_date'];
        $scheduler->page_groups = $data['page_groups'];
        if ($scheduler->save()) {
            return [true, $scheduler];
        }
        return [false];
    }
}