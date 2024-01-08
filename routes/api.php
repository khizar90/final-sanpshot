<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user-verify' , [AuthController::class , 'userVerify']);
Route::post('user-register' , [AuthController::class , 'register']);
Route::post('user-login' , [AuthController::class , 'login']);
Route::post('recover-verify' , [AuthController::class , 'recover']);
Route::get('recover-otp-verify' , [AuthController::class , 'recoverVerify']);
Route::post('new-password' , [AuthController::class , 'newPassword']);
Route::post('change-password/{user_id}' , [AuthController::class , 'changePassword']);
Route::post('edit-profile/{user_id}' , [AuthController::class , 'editProfile']);

Route::get('countries/{search?}' , [AuthController::class , 'countries']);
Route::get('cities/{country}/{search?}' , [AuthController::class , 'getCities']);




Route::get('help-video' , [AuthController::class , 'helpVedio']);
Route::get('splash' , [CategoryController::class , 'category']);
Route::get('video-levels' , [AuthController::class , 'level']);





Route::post('send-message' , [UserController::class , 'sendMessage']);
Route::post('conversation/{id}' , [UserController::class , 'conversation']);


Route::get('dashboard/{id}' , [UserController::class , 'dashboard']);
Route::get('list-task/{id}' , [UserController::class , 'listTask']);
Route::get('accept-task/{user_id}/{task_id}' , [UserController::class , 'acceptTask']);
Route::get('user-task-new/{id}' , [UserController::class , 'userTaskNew']);
Route::get('user-task-approve/{id}' , [UserController::class , 'userTaskApprove']);
Route::get('user-cashed-task/{id}' , [UserController::class , 'userCashedTask']);
Route::get('detail-task/{id}' , [UserController::class , 'detailTask']);
Route::post('add-question' , [UserController::class , 'addQuestion']);



Route::get('questions/{id}' , [UserController::class , 'question']);

Route::post('add-video/{id}' , [UserController::class , 'addVideo']);
Route::get('my-videos/{id}' , [UserController::class , 'myVideo']);
Route::get('videos-status/{id}/{status}' , [UserController::class , 'videoStatus']);
Route::get('declined/{id}' , [UserController::class , 'declinedVideo']);
Route::get('video-detail/{id}' , [UserController::class , 'videoDetail']);


Route::get('notification/{id}' , [UserController::class , 'notification']);

Route::get('bonus-read/{id}' , [UserController::class , 'bonusRead']);

Route::post('withdraw/{id}' , [UserController::class , 'withdraw']);
Route::get('withdraw-history/{id}' , [UserController::class , 'withdrawHistory']);
Route::get('unread-notification/{id}' , [UserController::class , 'unreadNotification']);





Route::get('logout/{id}' , [AuthController::class , 'logout']);






