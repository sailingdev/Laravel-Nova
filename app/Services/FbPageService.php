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
    public function updateOrCreateMultipleRows(array $data): ?bool
    { 
        $skip = ['112005480631100']; 
        foreach ($data as $row) {
            if (!in_array($row->id, $skip)) {
                FbPage::updateOrCreate([
                    'page_name' => $row->name,
                    'page_id' => $row->id 
                ], [
                    'page_name' => $row->name,
                    'page_id' => $row->id
                ]);
            }
        }
        return true;
    }
}