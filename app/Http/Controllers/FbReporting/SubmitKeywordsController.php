<?php

namespace App\Http\Controllers\FbReporting;
use App\Http\Requests\FbReporting\SubmitKeywords\SubmitKeywordsRequest;
use App\Labs\StringManipulator;
use App\Services\SubmittedKeywordService;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\SubmitKeywords\DeleteKeywordRequest;
use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;
use Illuminate\Http\Request;

class SubmitKeywordsController extends Controller
{
    /**
     * @param SubmitKeywordsRequest $request
     * @param StringManipulator $sm
     * @param SubmittedKeywordService $sks
     * 
     * @return Illuminate\Http\Response
     */
    public function submit(SubmitKeywordsRequest $request, StringManipulator $sm, SubmittedKeywordService $sks)
    {
       
        $rawKeywords = $request->keywords; 
        $prepKeywords = $sm->generateArrayFromString(str_replace("\n", '<br />',  $rawKeywords), '<br />');

        $process = $sks->submit($prepKeywords, $request->market);
        
        if ($process[0] == false) {
            return $this->errorResponse('An error occured. Please try again', $process[1]);
        }
        ProcessCampaignsFromSubmittedKeywordsJob::dispatch($process[2]);
        
        return $this->successResponse('Keywords submitted successfully. Batch processing in progress', $process[1]);
    }

    /**
     * Load keyword batches
     *
     * @param Request $request
     * @param SubmittedKeywordService $sks
     * 
     * @return Illuminate\Http\Response
     */
    public function loadKeywordBatches(Request $request, SubmittedKeywordService $sks)
    {
        return $this->successResponse('Data returned successfully', $sks->loadKeywordBatches());
    }

    /**
     * @param DeleteKeywordRequest $request
     * @param SubmittedKeywordService $sks
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function delete(DeleteKeywordRequest $request, SubmittedKeywordService $sks)
    {
        if (!$sks->deleteKeyword($request->id)) {
            return $this->errorResponse('An error occured. Please try again');
        }
        return  $this->successResponse('Keyword deleted successfully');
    }
    
}
