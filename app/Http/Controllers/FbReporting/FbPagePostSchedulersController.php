<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\FbPagePostScheduler\CreateScheduleRequest;
use App\Http\Requests\FbReporting\FbPagePostScheduler\DeletePostSchedulerRequest;
use App\Http\Resources\FbReporting\FbPagePostSchedulerResource;
use App\Revenuedriver\FacebookPage;
use App\Services\FbReporting\FbPagePostSchedulerService;
use Illuminate\Http\Request;

class FbPagePostSchedulersController extends Controller
{
    public function getAllScheduled(Request $request, FbPagePostSchedulerService $fbPagePostSchedulerService)
    {  
        return $this->successResponse('Data returned successfully', 
            FbPagePostSchedulerResource::collection($fbPagePostSchedulerService->getAllScheduled()));
    }

    /**
     * @param DeletePostSchedulerRequest $request
     * @param FbPagePostSchedulerService $fbPagePostSchedulerService
     * 
     * @return JsonResponse
     */
    public function delete(DeletePostSchedulerRequest $request, FbPagePostSchedulerService $fbPagePostSchedulerService)
    {   
        if (!$fbPagePostSchedulerService->deleteRow($request->id)) {
            return $this->errorResponse('An error occured. Please try again'); 
        }
        return $this->successResponse('Schedule has been successfully deleted');
    }

    public function create(CreateScheduleRequest $request,  FbPagePostSchedulerService $fbPagePostSchedulerService) 
    {
        $new = $fbPagePostSchedulerService->createSchedule([
            'start_date' => $request->start_date,
            'fb_page_post_id' => $request->fb_page_post_id,
            'page_groups' => $request->page_groups
        ]);
        if ($new[0] == false) {
            return $this->errorResponse('An error occured. Please try again');
        }
        return $this->successResponse('Schedule has been successfully created', new FbPagePostSchedulerResource($new[1]));
    }
}
