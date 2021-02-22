<?php

namespace App\Services;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\FbReporting\SubmittedKeyword;

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
                'market' => $market
            ]);
        }
        DB::beginTransaction();

        SubmittedKeyword::insert($data);

        DB::commit();

        return $batchId;
    }

    /**
     * @return string
     */
    protected function createBatchId(): string
    {
        return (string) Str::orderedUuid();
    }

}