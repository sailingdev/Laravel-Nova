<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Services\FbPageService;
use Illuminate\Http\Request;

class FbPagesController extends Controller
{
    /**
     * @param Request $request
     * @param FbPageService $fbPageService
     * 
     * @return JsonResponse
     */
    public function loadPageGroups(Request $request, FbPageService $fbPageService)
    {
        return $this->successResponse('Data returned successfully', $fbPageService->groupPage());
    }
}
