<?php

namespace App\Http\Controllers\FbReporting;
use App\Http\Requests\FbReporting\SubmitKeywordsRequest;
use App\Labs\StringManipulator;
use App\Services\SubmittedKeywordService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubmitKeywordsController extends Controller
{
    public function submit(SubmitKeywordsRequest $request, StringManipulator $sm, SubmittedKeywordService $sks)
    {
        $rawKeywords = $request->keywords; 
        $prepKeywords = $sm->generateArrayFromString(str_replace("\n", '<br />',  $rawKeywords), '<br />');

        $process = $sks->submit($prepKeywords, $request->market);

        if (!$process) {
            return $this->errorResponse('An error occured. Please try again');
        }
        return $this->successResponse('Keywords submitted successfully. Batch processing in progress', $process);
    }

    /**
     * Load keyword batches
     *
     * @param Request $request
     * @param SubmittedKeywordService $sks
     * 
     * @return Response
     */
    public function loadKeywordBatches(Request $request, SubmittedKeywordService $sks)
    {
        return $this->successResponse('Data returned successfully', $sks->loadKeywordBatches());
    }
}
