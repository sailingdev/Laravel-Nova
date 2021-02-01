<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Labs\StringManipulator;
use App\Services\TypeDailyPerfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeDailyPerfsController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\TypeDailyPerfService $typeDailyPerfService
     * 
     * @return \Illuminate\Http\Response
     */
    public function dailySummaryByTypeTags(Request $request, TypeDailyPerfService $typeDailyPerfService, 
        StringManipulator $stringManipulator)
    {     
        $typeTags = $request->type_tag;
        // $request->session()->forget(['daily_summary_by_tags', 'website_break_down', 'campaign_break_down']);
       

        $dailySummaryByTags = $typeDailyPerfService->loadDailySummaryByTags($typeTags, $request->start_date, $request->end_date);

        $websiteBreakDown = $typeDailyPerfService->loadWebsiteDailySummary($typeTags, $request->start_date, $request->end_date);

        $campaignBreakDown = $typeDailyPerfService->loadCampaignDailySummary($typeTags, $request->start_date, $request->end_date);
        
        return $this->successResponse('Daily summary returned successfully', [
            'daily_summary' => [
                'list' => $typeDailyPerfService->prepareData($dailySummaryByTags),
                'metrics' => [
                    'tot_spend' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags,'tot_spend'),
                    'tot_revenue' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags, 'tot_revenue'),
                    'tot_profit' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags, 'tot_profit'),
                    'tot_roi' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags, 'tot_roi', 'percentage'),
                ],
                'website_break_down' => $websiteBreakDown,
                'campaign_break_down' => $campaignBreakDown, 
            ]
        ]);
    }

    public function typeTags(Request $request, TypeDailyPerfService $typeDailyPerfService)
    {
        return $this->successResponse('Type tags returned successfully', [
            'type_tags' => $typeDailyPerfService->getAllTypeTags()
        ]);
    }
}
