<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::group(['middleware' => ['cors'], 'prefix' => 'v1'], function ()
// {
    Route::group(['middleware' => 'api','prefix' => 'auth'], function ()
    {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });


    // Route::group(['middleware' => 'auth:api'], function()
    // {
        Route::post('add-farm', [FarmController::class, 'store']);
        Route::get('show-all-farms', [FarmController::class, 'show_all']);
        Route::get('show-single-farm', [FarmController::class, 'show']);
    //});
//});
