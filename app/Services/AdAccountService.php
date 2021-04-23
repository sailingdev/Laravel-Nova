<?php

namespace App\Services;

use App\Labs\StringManipulator;
use App\Models\FbReporting\AdAccount;
use App\Models\FbReporting\AdText;
use App\Revenuedriver\FacebookCampaign;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdAccountService
{
    /**
     * @return \App\Models\FbReporting\AdAccount
     */
    public function getAll()
    {
        return AdAccount::all();
    }

    /**
     * @param string $accountId
     * 
     * @return AdAccount|null
     */
    public function getRowByAccountId(string $accountId): ?AdAccount
    {
        return AdAccount::where('account_id', preg_replace("#[^0-9]#i", "", $accountId))->first();
    }

    /**
     * @param string $domain
     * 
     * @return string|null
     */
    public function determineTargetAccountByFeed(string $feed): ?string
    {
        $fbc = new FacebookCampaign; 
        $targetAccounts = $fbc->getTargetAccounts();
        $wbs = new WebsiteService;
        foreach ($targetAccounts as $targetAccount) {
            $row = AdAccount::where('account_id', preg_replace('#[^0-9]#i', "", $targetAccount))->first();
            if ($row !== null) {
                $rowDomain = $fbc->getSiteFromAdAccountConfigurations($row->configurations);
                // find the feed for this domain 
                $website = $wbs->getRowByDomain($rowDomain); 
                 
                if ($website !== null) {
                    if ($website->feed == strtolower($feed)) {
                       return $targetAccount;
                    }
                }
            }
        }
        return null;
    }
}