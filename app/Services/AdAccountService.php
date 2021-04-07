<?php

namespace App\Services;

use App\Labs\StringManipulator;
use App\Models\FbReporting\AdAccount;
use App\Models\FbReporting\AdText;
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
}