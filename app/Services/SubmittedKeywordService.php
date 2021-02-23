<?php

namespace App\Services;

use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\FbReporting\SubmittedKeyword;
use App\Revenuedriver\FacebookCampaign;

class SubmittedKeywordService
{
    public function submit(array $keywords, string $market)
    {
        $batchId = $this->createBatchId();
 
        $data = [];
        foreach ($keywords as $keyword) {
            array_push($data, [
                'batch_id' => $batchId,
                'keyword' => $keyword,
                'market' => $market,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }
        // DB::beginTransaction();

        // SubmittedKeyword::insert($data);

        // DB::commit();

        ProcessCampaignsFromSubmittedKeywordsJob::dispatch($data);

        return $batchId;
    }

    public function loadKeywordBatches()
    {
        
        // ->where('created_at', '>', 
        //     Carbon::now()->subHours(3)->toDateTimeString()
        // );
        
        $batches = SubmittedKeyword::select('batch_id')->distinct()->get();
        $data = [];
        if (count($batches) > 0) {
            foreach ($batches as $batch) {
                $rel = new \stdClass;
                $rel->batch_id = $batch->batch_id;
                $batchKeywords = SubmittedKeyword::where('batch_id', $batch->batch_id)
                ->select('keyword', 'status', 'market', 'created_at', 'updated_at')
                ->get();
                $keywordsInProgress = 0;
                foreach ($batchKeywords as $batchKeyword) {
                    if ($batchKeyword->status === 'processing') {
                        $keywordsInProgress++;
                    }
                }
                $rel->batch_status = $keywordsInProgress > 0 ? 'processing' : 'processed';
                $rel->keywords = $batchKeywords;
                array_push($data, $rel);
            }
        }
         
        return $data;
    }

    /**
     * Create batch ID
     *
     * @return string
     */
    protected function createBatchId(): string
    {
        return (string) Str::orderedUuid();
    }

    public function processSubmittedKeywords($data)
    { 
        $facebookCampaign = new FacebookCampaign;
        $account3Campaigns = $facebookCampaign->loadCampaign('370837070102255');
    }

}