<?php 

namespace App\Services\FbReporting;

use App\Models\FbReporting\FbPagePost;
use App\Models\FbReporting\FbPagePostScheduler;
use App\Revenuedriver\FacebookPage;
use App\Services\FbPageService;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $scheduler->page_groups = $data['page_groups'];
        
        if ($scheduler->save()) {
            return [true, $scheduler];
        }
        return [false];
    }

    public function getAllScheduled()
    {
        return FbPagePostScheduler::where('start_date', '>=', Carbon::now())->orderBy('start_date', 'asc')->get();        
    }

    /**
     * @param array $data
     * @param mixed $rowId
     * 
     * @return array
     */
    public function updateSchedule(array $data, $rowId): array
    { 
        $fbPagePostScheduler = FbPagePostScheduler::where('id', $rowId)->first();
        foreach ($data as $key => $value) {
            $fbPagePostScheduler->{$key} = $value;
        }
        if ($fbPagePostScheduler->save()) {
            return [true, $fbPagePostScheduler];
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

    public function runSchedule()
    { 
        $schedules = FbPagePostScheduler::where('start_date', '<=', Carbon::now())
        ->where('status', '!=', 'processed')
        ->with(['fbPagePost'])
        ->get();
        $facebookPageExternal = new FacebookPage;
        $fbPageService = new FbPageService;
    
        if (count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                 
                $targetGroups = (array) $schedule->page_groups;
              
                if (count($targetGroups) > 0) { 
                    foreach ($targetGroups as $targetGroup) {
                        $groupLimit = $fbPageService->getGroupQueryLimits(preg_replace("#[^0-9]#i", "", $targetGroup));
                      
                        $facebookPages = $fbPageService->getByLimits($groupLimit[0], $groupLimit[1]);
                        
                        foreach ($facebookPages as $facebookPage) {  

                            $pageId = $facebookPage->page_id; 
                          
                            $fd = [];
                          

                                Log::info('Will create into ', [$facebookPage->page_name, $facebookPage->page_id]);

                                if ($schedule->fbPagePost->media !== null) {

                                    // upload a photo
                                    $createPhoto = $facebookPageExternal->createPagePhoto([], [
                                        'no_story' => true,
                                        'url' => $schedule->fbPagePost->media
                                    ], $pageId);
                                    
                                    if ($createPhoto[0] === true && isset($createPhoto[1]->id)) {
                                        $fd['object_attachment'] = $createPhoto[1]->id; 
                                    }
                                    else {
                                        Log::info('Photo could not be created for the page with id :: ' . $pageId, [$createPhoto[1]]);
                                    } 
                                } 
                                $fd['message'] = $schedule->fbPagePost->text;
                                $fd['url'] = $schedule->fbPagePost->url;
                                $createPost = $facebookPageExternal->createPagePost([], $fd, $pageId); 
    
                                if ($createPost[0] === false) {
                                    Log::info('An error occured. Post was not created for schedule with ID: ' . $schedule->id, [$createPost[1]]);
                                } 
                            
                        }
                    
                    }
                } 

                $this->updateSchedule([
                    'status' => 'processed'
                ], $schedule->id);
            }
        }
    }

    

    
}