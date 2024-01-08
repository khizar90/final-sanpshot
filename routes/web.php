<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Models\Admin;
use App\Models\AdminTask;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/insert', function () {
    $user = new Admin();
    $user->name = 'Dwight Watson';
    $user->email = 'admin@snapshotgarage.com';
    $user->password = Hash::make('Password1');
    $user->save();
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('login');
    })->name('loginPage');

    Route::post('login', [AdminLoginController::class, 'login'])->name('login');
});



Route::prefix('dashboard')->middleware(['auth'])->name('dashboard-')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');

    Route::prefix('user')->name('user-')->group(function () {
        Route::get('/', [AdminController::class, 'users']);
        Route::get('profile/{id}', [AdminController::class, 'userProfile'])->name('profile');
        Route::get('export', [AdminController::class, 'exportCSV'])->name('export/csv');
        Route::post('add', [AdminController::class, 'addUser'])->name('add');
        Route::get('verify/{id}', [AdminController::class, 'userVerify'])->name('verify');
        Route::get('delete/{id}', [AdminController::class, 'deleteUser'])->name('delete');
        Route::get('bonus/{id}', [AdminController::class, 'userBonus'])->name('bonus');
        Route::get('message', [AdminController::class, 'message'])->name('message');
    });




    Route::prefix('level')->name('level-')->group(function () {
        Route::get('/', [AdminController::class, 'level']);
        Route::post('add', [AdminController::class, 'addLevel'])->name('add');
        Route::post('edit/{id}', [AdminController::class, 'editLevel'])->name('edit');
        Route::get('delete/{id}', [AdminController::class, 'deleteLevel'])->name('delete');
    });

    Route::prefix('help')->name('help-')->group(function () {
        Route::get('videos', [AdminController::class, 'videos']);
        Route::post('upload', [AdminController::class, 'uploadVideo'])->name('upload');
        Route::post('update/{id}', [AdminController::class, 'updateVideo'])->name('update');
        Route::get('delete/{id}', [AdminController::class, 'deleteVedio'])->name('delete');
    });



    Route::prefix('message')->name('message-')->group(function () {
        Route::get('/', [AdminController::class, 'chat']);
        Route::get('conversation/{id}', [AdminController::class, 'conversation'])->name('conversation');
        Route::post('send', [AdminController::class, 'sendMessage'])->name('send');
    });


    Route::prefix('faq')->name('faq-')->group(function () {
        Route::get('/', [AdminController::class, 'faqs']);
        Route::post('add', [AdminController::class, 'addFaq'])->name('add');
        Route::get('delete/{id}', [AdminController::class, 'deleteFaq'])->name('delete');
    });


    Route::prefix('question')->name('question-')->group(function () {
        Route::get('/', [AdminController::class, 'question']);
        Route::post('add', [AdminController::class, 'addQuestion'])->name('add');
        Route::post('edit/{id}', [AdminController::class, 'editQuestion'])->name('edit');
        Route::get('delete/{id}', [AdminController::class, 'deleteQuestion'])->name('delete');
    });



    Route::prefix('task')->name('task-')->group(function () {
        Route::get('list', [AdminTaskController::class, 'listTask'])->name('list');
        Route::get('add', [AdminTaskController::class, 'create'])->name('add');
        Route::post('add', [AdminTaskController::class, 'addTask']);
        Route::get('edit/{id}', [AdminTaskController::class, 'editTask'])->name('edit');
        Route::get('delete/{id}', [AdminTaskController::class, 'taskDelete'])->name('delete');

        Route::post('edit/{id}', [AdminTaskController::class, 'updateTask']);
        Route::get('active/{id}', [AdminTaskController::class, 'changeStatus'])->name('active');

        Route::get('list/user/videos/{id}', [AdminTaskController::class, 'userVideo'])->name('user-videos');
        Route::get('list/user/list/{id}', [AdminTaskController::class, 'users'])->name('user-list');
        Route::get('assign/{user_id}/{task_id}', [AdminTaskController::class, 'assignUser'])->name('assign');
    });



    Route::get('approve-video/{id}', [AdminTaskController::class, 'approveVideo'])->name('approve-video');
    Route::get('decline-video/{id}', [AdminTaskController::class, 'declineVideo'])->name('decline-video');
    Route::get('delete-video/{id}', [AdminTaskController::class, 'deleteVideo'])->name('delete-video');
    Route::get('reward/{id}/{task_id}', [AdminTaskController::class, 'reward'])->name('reward');


    // Route::get('task/user/{task_id}', [AdminTaskController::class, ''])->name('task-user');

    Route::get('point-value', [AdminController::class, 'snapPoint'])->name('point-value');
    Route::post('point-value', [AdminController::class, 'snapPointadd']);
    Route::get('logout', [AdminLoginController::class, 'logout'])->name('logout');
});
