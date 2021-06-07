<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\CreateCampaignFromRelatedRequest;
use App\Jobs\FbReporting\CreateCampaignsFromRelatedJob;
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
        return $this->successResponse('Data returned successfully', $sks->loadToBeCreated());
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

    public function createCampaignFromRelatedTypeTag(CreateCampaignFromRelatedRequest $request, SubmittedKeywordService $sks)
    { 
        $data = $request->all()['data'];
       
        $sks->updateRow($data['batch_id'], $data['keyword'], [
            'status' => 'pending'
        ]);  
        CreateCampaignsFromRelatedJob::dispatch($data);
        return $this->successResponse('Request was successful. Batch processing in progress');
    }
}
