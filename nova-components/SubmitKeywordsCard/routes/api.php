<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FbReporting\SubmitKeywordsController;

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

Route::post('/submit-keywords', [SubmitKeywordsController::class, 'submit']);
Route::get('/load-keyword-batches', [SubmitKeywordsController::class, 'loadKeywordBatches']);
