<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::post('sign-in', [AuthController::class,'signIn']);
Route::post('create-account', [AuthController::class,'createAccount']);
Route::post('verify-pin', [AuthController::class,'verifyPin']);

Route::group(['middleware' => ['auth:api','admin']], function (){
    Route::post('send-email', [AuthController::class,'sendEmail']);
});

Route::group(['middleware' => ['auth:api','user']], function (){
    Route::post('update-profile', [AuthController::class,'updateProfile']);
});
