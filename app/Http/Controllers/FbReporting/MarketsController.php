<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Services\MarketService;
use Illuminate\Http\Request;

class MarketsController extends Controller
{
    public function index(Request $request, MarketService $marketService)
    {
        return $this->successResponse('Data returned successfully', $marketService->getAll());
    }
}
