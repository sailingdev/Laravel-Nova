<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Services\TypeDailyPerfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeDailyPerfsController extends Controller
{

    public function dailySummary(Request $request, TypeDailyPerfService $typeDailyPerfService)
    {    
        $request->session()->forget('daily_summary');
        return $this->successResponse('Daily summary returned successfully', [
            'daily_summary' => $request->session()->get('daily_summary', function () use ($typeDailyPerfService) {
                return $typeDailyPerfService->getDailySummary();
            })
        ]);
    }
}
