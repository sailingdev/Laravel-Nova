<?php

namespace App\Http\Controllers\FbReporting;
use App\Http\Requests\FbReporting\SubmitKeywords\SubmitKeywordsRequest;
use App\Labs\StringManipulator;
use App\Services\SubmittedKeywordService;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\SubmitKeywords\DeleteKeywordRequest;
use App\Jobs\FbReporting\CreateCampaignsFromTemplateJob;
use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;
use Illuminate\Http\Request;
use stdClass;

class CreateCampaignsFromTemplateController extends Controller
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
        
        $prepArrObj = [];
        foreach ($prepKeywords as $prepKeyword) {
            $dt = new stdClass;
            $dt->batch_id = null;
            $dt->keyword = $prepKeyword;
            $prepArrObj[] = $dt;
        }
        CreateCampaignsFromTemplateJob::dispatch($prepArrObj, $request->market);
        
        return $this->successResponse('Keywords submitted successfully. Campaign duplication in progress', null);
    }

    
    
}
