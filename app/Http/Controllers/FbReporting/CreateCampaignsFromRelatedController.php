<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\CreateCampaignFromRelatedRequest;
use App\Jobs\FbReporting\ProcessPendingBatchesUsingTypeTagsJob;
use App\Services\SubmittedKeywordService;
use Illuminate\Http\Request;

class CreateCampaignsFromRelatedController extends Controller
{
    /**
     * @param Request $request
     * @param SubmittedKeywordService $sks
     * 
     * @return Illuminate\Http\Response
     */
    public function toProcess(Request $request, SubmittedKeywordService $sks)
    {
        return $this->successResponse('Data returned successfully', $sks->loadBatchSummaries());
    }

    /**
     * @param Request $request
     * @param SubmittedKeywordService $sks
     * 
     * @return Illuminate\Http\Response
     */
    public function history(Request $request, SubmittedKeywordService $sks)
    {
        return $this->successResponse('Data returned successfully', $sks->loadBatchHistory());
    }

    public function processPendingBatches(CreateCampaignFromRelatedRequest $request, SubmittedKeywordService $sks)
    {
        foreach ($request->all()['data'] as $key => $keyword) {
            // $sks->updateRow($keyword->batch_id, $keyword->keyword, [
            //     'status' => 'processing'
            // ]);
        }
        ProcessPendingBatchesUsingTypeTagsJob::dispatch($request->all()['data']);
        return $this->successResponse('Request was successful. Batch processing in progress');
    }
}
