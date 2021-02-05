<?php

use App\Http\Controllers\FbReporting\CampaignDetailsController;
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

Route::get('campaign-details-by-type-tags/feed-totals', [CampaignDetailsController::class, 'feedTotals']);
Route::get('campaign-details-by-type-tags/daily-totals', [CampaignDetailsController::class, 'dailyTotals']);
Route::get('campaign-details-by-type-tags/website-totals', [CampaignDetailsController::class, 'websiteTotals']);