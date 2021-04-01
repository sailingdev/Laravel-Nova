<?php

namespace App\Services;

use App\Models\FbReporting\FbPage;
use App\Models\FbReporting\Rpc;
use Carbon\Carbon;

class FbPageService
{
    /**
     * @param array $data
     * 
     * @return bool|null
     */
    public function updateOrCreateMultipleRows(array $data, $environment='rd'): ?bool
    { 
        $skip = ['112005480631100']; 
        foreach ($data as $row) { 
            if (!in_array($row->id, $skip)) {
                FbPage::updateOrCreate([
                    'page_name' => $row->name,
                    'page_id' => $row->id,
                    'environment' => $environment
                ], [
                    'page_name' => $row->name,
                    'page_id' => $row->id,
                    'environment' => $environment
                ]);
            }
        }
        return true;
    }

    /**
     * @param mixed $rowId
     * @param mixed $newCount
     * 
     * @return 
     */
    public function updateRunningAdsCount($rowId, $newCount)
    { 
        return FbPage::where('id', $rowId)->update([
            'running_ads' => $newCount
        ]);
    }

     /**
     * @return [type]
     */
    public function getAll()
    {
        return FbPage::all();
    }
}