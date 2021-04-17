<?php

namespace App\Services\Tools;

use App\Models\FbReporting\CampaignOptimizeTracker;
use App\Models\FbReporting\Rpc;
use App\Models\Tools\ToolsExecution;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ToolsExecutionService
{

    public function hasRunDailyReportGeneratorMSS(): bool
    { 
        if (ToolsExecution::where('tool_name', 'dailyreportgeneratorMSS-daily')
        ->where('date', date('Y-m-d') . ' 00:00:00')
        ->where('status', 'EXECUTED')->count() > 0) {
            return true;
        }
        return false;
    }
}