<?php

use App\Http\Controllers\FbReporting\CreateCampaignsFromRelatedController;
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

Route::get('/load-batches-to-process', [CreateCampaignsFromRelatedController::class, 'toProcess']);
Route::get('/processed-batch-history', [CreateCampaignsFromRelatedController::class, 'history']);
Route::post('/create-campaign', [CreateCampaignsFromRelatedController::class, 'processPendingBatches']);
