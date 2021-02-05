<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Labs\StringManipulator;
use App\Services\TypeDailyPerfService;
use Illuminate\Http\Request;

class CampaignDetailsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\TypeDailyPerfService $typeDailyPerfService
     * 
     * @return \Illuminate\Http\Response
     */
    public function feedTotals(Request $request, TypeDailyPerfService $typeDailyPerfService)
    {     
        $typeTag = $request->type_tag; 
       
        $data = $typeDailyPerfService->loadCampaignDetailsFeedTotals($typeTag, $request->start_date, $request->end_date);
        
        return $this->successResponse('Daily summary returned successfully', [
            'feed_totals' => $typeDailyPerfService->prepareData($data)
        ]);
    }



    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\TypeDailyPerfService $typeDailyPerfService
     * 
     * @return \Illuminate\Http\Response
     */
    public function dailyTotals(Request $request, TypeDailyPerfService $typeDailyPerfService)
    {     
        $typeTag = $request->type_tag; 
    
        $data = $typeDailyPerfService->loadCampaignDetailsDailyTotals($typeTag, $request->start_date, $request->end_date);
         
        return $this->successResponse('Data returned successfully', [
            'list' => $typeDailyPerfService->prepareData($data),
            'metrics' => [
            'tot_spend' => $typeDailyPerfService->aggregateTrendMetricData($data,'tot_spend'),
            'tot_revenue' => $typeDailyPerfService->aggregateTrendMetricData($data, 'tot_revenue'),
            'tot_profit' => $typeDailyPerfService->aggregateTrendMetricData($data, 'tot_profit'),
            'tot_roi' => $typeDailyPerfService->aggregateTrendMetricData($data, 'tot_roi', 'percentage'),
            'tot_clicks' => $typeDailyPerfService->aggregateTrendMetricData($data, 'tot_clicks'),
            'tot_rpc' => $typeDailyPerfService->aggregateTrendMetricData($data, 'tot_rpc'),
            'tot_cpa' => $typeDailyPerfService->aggregateTrendMetricData($data, 'tot_cpa'),
            'target_cpa' => $typeDailyPerfService->loadCampaignDetailsTargetCpaTotals($typeTag)
            ],
        ]);
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\TypeDailyPerfService $typeDailyPerfService
     * 
     * @return \Illuminate\Http\Response
     */
    public function websiteTotals(Request $request, TypeDailyPerfService $typeDailyPerfService)
    {     
        $typeTag = $request->type_tag; 
       
        $data = $typeDailyPerfService->loadCampaignDetailsWesbiteTotals($typeTag, $request->start_date, $request->end_date);
        
        return $this->successResponse('Daily summary returned successfully', [
            'website_totals' => $typeDailyPerfService->prepareData($data)
        ]);
    }

}
