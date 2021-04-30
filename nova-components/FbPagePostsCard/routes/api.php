<?php

use App\Http\Controllers\FbReporting\FbPagePostsController;
use App\Http\Controllers\FbReporting\FbPagesController;
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

Route::get('/load-page-groups', [FbPagesController::class, 'loadPageGroups']);
Route::post('/submit-page-post', [FbPagePostsController::class, 'submit']);
