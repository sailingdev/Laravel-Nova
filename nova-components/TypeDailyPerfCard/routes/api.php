<?php

use App\Http\Controllers\FbReporting\TypeDailyPerfsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

Route::post('daily-summary-by-type-tags/feed-totals', [TypeDailyPerfsController::class, 'dailySummaryByTypeTagsFeedTotals']);
Route::post('daily-summary-by-type-tags/website-break-down', [TypeDailyPerfsController::class, 'dailySummaryByTypeTagsWebsiteBreakDown']);
Route::post('daily-summary-by-type-tags/campaign-break-down', [TypeDailyPerfsController::class, 'dailySummaryByTypeTagsCampaignBreakDown']);
