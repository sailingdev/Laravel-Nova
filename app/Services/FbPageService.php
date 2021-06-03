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
    public function create(string $pageName, string $pageId, $isPublished, $instagramId, $environment): ?bool
    {  
        FbPage::create([
            'page_name' => $pageName,
            'page_id' => $pageId,
            'instagram_id' => $instagramId,
            'environment' => $environment,
            'is_published' => $isPublished
        ]);
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

    public function getByPageId(string $pageId)
    { 
        return FbPage::where('page_id', $pageId)->first();
    }

    public function getByLimits(int $start, int $end)
    {
        return FbPage::orderBy('created_at', 'asc')->offset($start)->limit(50)->get();
    }

    public function updateData($data, $rowId)
    {
        return FbPage::where('id', $rowId)->update($data);
    }

    public function getRandomFbPage($environment='rd')
    {
        return FbPage::where('running_ads', '<', 250)->where('environment', $environment)->first();
    }

    /**
     * @return array
     */
    public function groupPage(): array
    {
        $tot = FbPage::count();
        $groups = [];
        if ($tot > 0 && $tot <= 50) {
            $groups['Group 1'];
        }
        else {
            $dv = round($tot / 50, 1);
            for ($i = 1; $i <= $dv; $i++) { 
                array_push($groups, 'Group ' . $i);
            }
        }
        return $groups;
    }
    
    /**
     * algo for computing limits
     * 
     * For group <= 1, start=1, end=50
     * For group > 1, end=(group * 50) + 1, start=end - 50
     * 
     * @param int $group
     * 
     * @return array
     */
    public function getGroupQueryLimits(int $group): array
    {
        $tot = FbPage::count();
        $end =  (int) $group *  50;

        if ((int) $group > 1) {
            $start = ($end - 50) + 1;
            return [$tot < $start ? $tot : $start, $end];
        }
        else {
            return [0, $end];
        }
    }
}