<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Http\Resources\FbReporting\FbPagePostSchedulerResource;
use App\Revenuedriver\FacebookPage;
use App\Services\FbReporting\FbPagePostSchedulerService;
use Illuminate\Http\Request;

class FbPagePostSchedulersController extends Controller
{
    public function getAllScheduled(Request $request, FbPagePostSchedulerService $fbPagePostSchedulerService)
    {
        // $fb = new FacebookPage;
        // $fb->loadBusinessAccountPages();
        return $this->successResponse('Data returned successfully', 
            FbPagePostSchedulerResource::collection($fbPagePostSchedulerService->getAllScheduled()));
    }
}
