<?php

namespace App\Services;

use App\Models\FbReporting\AdAccount;
use App\Models\FbReporting\AdText;
use Illuminate\Support\Facades\Cache;

class AdAccountService
{
    /**
     * @return [type]
     */
    public function getAll()
    {
        return AdAccount::all();
    }
}