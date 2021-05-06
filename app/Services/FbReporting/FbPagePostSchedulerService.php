<?php 

namespace App\Services\FbReporting;

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

    public function runSchedule()
    {
        $schedules = FbPagePostScheduler::where('start_date', '<>', Carbon::now())
        ->where('status', '!=', 'processed')
        ->with(['fbPagePost'])
        ->get();
        $facebookPageExternal = new FacebookPage;
        $fbPageService = new FbPageService;
        if (count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                $targetGroups = $schedule->page_groups;
                if (count($targetGroups) > 0) {
                    foreach ($targetGroups as $targetGroup) {
                        $groupLimit = $fbPageService->getGroupQueryLimits(preg_replace("#[^0-9]#i", "", $targetGroup));
                        $facebookPages = $fbPageService->getByLimits($groupLimit[0], $groupLimit[1]);
                       // for each of this page Id, post to page
                       foreach ($facebookPages as $facebookPage) { 
                             
                            $pageId = '101355112064132'; //$facebookPage->page_id;
                            if ($schedule->fbPagePost->media !== null) {

                               
                                
                                $albumId = null;

                                // see if this page has an album that it it's photos can be posted into
                                $pageAlbums = $facebookPageExternal->getPageAlbums(['id', 'name'], [], $pageId);
                                $shouldCreateNewAlbum = true;
                                if ($pageAlbums[0] === true) {
                                    if (isset($pageAlbums[1]->data)) {
                                        // search for a timeline album
                                        $search = array_filter($pageAlbums[1]->data, function ($album) {
                                            return $album->name === 'Timeline';
                                        });
                                        if (count($search) > 0) {
                                            $shouldCreateNewAlbum = false;  
                                            $albumId = $search[0]->id;
                                        }
                                    }
                                }
                                else {
                                    $shouldCreateNewAlbum = false;
                                    Log::info('An error occured while reading the albums for page with ID:' . $pageId, [$pageAlbums[1]]);
                                }
                               
                                if ($shouldCreateNewAlbum === true) {
                                    // $file =  new File(Storage::disk('public')->path('fb_posts/' . $schedule->fbPagePost->media));
                                   dd($schedule->fbPagePost->media);
                                    // process and create a new album
                                    $newAlbum = $facebookPageExternal->createPageAlbum([
                                        'no_story' => true,
                                        'url' => $file->getRealPath()
                                    ], [], $pageId);

                                    if ($newAlbum[0] === true) {
                                        $albumId = $newAlbum[1];
                                    }
                                    else {
                                        Log::info('An error occured while creating an album for page with ID:' . $pageId, [$newAlbum[1]]);
                                    }
                                }

                                dd($albumId);
                                if ($albumId != null) {
                                    // create a new photo

                                }
                            } 
                            
                            $createPost = $facebookPageExternal->createPagePost([
                                'message' => $schedule->fbPagePost->text,
                                'url' => $schedule->fbPagePost->url,
                                'media' => $schedule->fbPagePost->media
                            ], [], $pageId);  //$facebookPage->page_id

                            dd('wale', $createPost);
                       }
                    }
                } 
                dd($targetGroups);
            }
        }
    }

    

    
}