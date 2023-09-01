<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\SalesController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/sale','API\SalesController@buy');
Route::post('/sale', 'API\SalesController@pur');
Route::post('/sale',[App\Http\Controllers\API\SalesController::class, 'buy']);