<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
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
}
