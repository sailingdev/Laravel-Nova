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
        $superArray = [];
        $data = $request->all()['data'];
        foreach ($data as $keyword) {
           
            // Get all the records that belongs to this batch ID
            $toBeCreated = $sks->getToBeCreatedCampaignsByBatchIdAndKeyword($keyword->batch_id, $keyword->keyword);
            if (count($toBeCreated) > 0) {
                foreach ($toBeCreated as $campaign) {
                    $sks->updateRow($campaign->batch_id, $campaign->keyword, [
                        'status' => 'processing'
                    ]); 
                    $obj = new \stdClass;
                    $obj->keyword = $campaign->keyword;
                    $obj->type_tag = $keyword->type_tag;
                    $obj->batch_id = $campaign->batch_id;
                    $obj->feed = $campaign->feed;
                    $obj->id = $campaign->id;
                    $superArray[] = $obj;
                } 
            }
            
        }  
        ProcessPendingBatchesUsingTypeTagsJob::dispatch($superArray);
        return $this->successResponse('Request was successful. Batch processing in progress');
    }
}
