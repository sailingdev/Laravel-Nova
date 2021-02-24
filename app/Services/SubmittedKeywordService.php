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

        $accounts = ['370837070102255', '426462675029843']; // 3 & 21
        $campaignCombo = [];
        foreach ($accounts as $account) {
            $accountCampaigns = $facebookCampaign->loadCampaign($account, [
                'name', 'status'
            ]);
            if ($accountCampaigns[0] !== false) {
                foreach ($accountCampaigns[1] as $campaign) {
                    $n = [
                        'campaign_name' => $campaign->name,
                        'campaign_status' => $campaign->status,
                        'campaign_id' => $campaign->id,
                        'account_id' => $account
                    ];
                    array_push($campaignCombo, $n);
                }
            }
        } 
        $rpcService = new RpcService;
        foreach ($campaignCombo as $campaign) {
           $campaignExtracts = $facebookCampaign->extractDataFromCampaignName($campaign['campaign_name']);
           $campaignKeyword = $campaignExtracts['keyword'];
           dd($rpcService->countKeyword($campaignKeyword));
        }    
        dd($campaignCombo);
    }

}