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
    public function dailySummaryByTypeTagsFeedTotals(Request $request, TypeDailyPerfService $typeDailyPerfService, 
        StringManipulator $sm)
    {     
        $typeTags = $sm->generateStringFromArray($request->type_tag, ','); 
       
        $dailySummaryByTags = $typeDailyPerfService->loadHomepageDailySummaryByTags($typeTags, $request->start_date, $request->end_date);
        
        return $this->successResponse('Daily summary returned successfully', [
            'daily_summary' => [
                'list' => $typeDailyPerfService->prepareData($dailySummaryByTags),
                'metrics' => [
                    'tot_spend' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags,'tot_spend'),
                    'tot_revenue' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags, 'tot_revenue'),
                    'tot_profit' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags, 'tot_profit'),
                    'tot_roi' => $typeDailyPerfService->aggregateTrendMetricData($dailySummaryByTags, 'tot_roi', 'percentage'),
                ],
            ]
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\TypeDailyPerfService $typeDailyPerfService
     * 
     * @return \Illuminate\Http\Response
     */
    public function dailySummaryByTypeTagsWebsiteBreakDown(Request $request, TypeDailyPerfService $typeDailyPerfService, 
        StringManipulator $sm)
    {      
        $typeTags = $sm->generateStringFromArray($request->type_tag, ','); 

        $websiteBreakDown = $typeDailyPerfService->loadWebsiteDailySummary($typeTags, $request->start_date, $request->end_date);
 
        return $this->successResponse('Daily summary returned successfully', [
            'daily_summary' => [ 
                'website_break_down' => $websiteBreakDown
            ]
        ]);
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\TypeDailyPerfService $typeDailyPerfService
     * 
     * @return \Illuminate\Http\Response
     */
    public function dailySummaryByTypeTagsCampaignBreakDown(Request $request, TypeDailyPerfService $typeDailyPerfService, 
        StringManipulator $sm)
    {      
        $typeTags = $sm->generateStringFromArray($request->type_tag, ','); 

       $campaignBreakDown = $typeDailyPerfService->loadCampaignDailySummary($typeTags, $request->start_date, $request->end_date);
 
        return $this->successResponse('Daily summary returned successfully', [
            'daily_summary' => [ 
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

     /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\TypeDailyPerfService $typeDailyPerfService
     * 
     * @return \Illuminate\Http\Response
     */
    public function dailySummaryByTypeTagsAllWebsiteBreakDown(Request $request, TypeDailyPerfService $typeDailyPerfService, 
        StringManipulator $sm)
    {      
        $typeTags = $sm->generateStringFromArray($request->type_tag, ','); 

        $websiteBreakDown = $typeDailyPerfService->loadAllWebsiteDailySummary($typeTags, $request->start_date, $request->end_date);
 
        return $this->successResponse('Daily summary returned successfully', [
            'daily_summary' => [ 
                'website_break_down' => $websiteBreakDown
            ]
        ]);
    }
}
