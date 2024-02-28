<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SuperAdmin\AddAdminController;
use App\Http\Controllers\SuperAdmin\AddSuperAdminController;
use App\Http\Controllers\SuperAdmin\DeleteAdminController;
use App\Http\Controllers\SuperAdmin\DeleteUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
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
Route::any('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->namespace('App\Http\Controllers\AuthControllers')->group(function () {
    // dd(SignUpController::class);
    Route::post('logout', 'LogoutController@logout');
    Route::post('login', 'LoginController@loginUser')->name('login');
    Route::post('register','SignUpController@signup');
    Route::post('delete-account', 'DeleteAccountController@index');
    Route::post('check-email','SignUpController@checkEmail');
    Route::post('password/email', 'PasswordResetLinkController@store');
    Route::post('password/reset', 'NewPasswordController@store');
});
Route::prefix('questions')->group(function () {
    Route::get('/all', [QuestionController::class, 'index'])->name('questions.all');
    Route::post('/ask', [QuestionController::class, 'askQuestion'])->name('answer.create')->middleware('auth:sanctum');;
    Route::post('/update-ask', [QuestionController::class, 'updateAskQuestion'])->name('answer.update');
    Route::post('/comment', [QuestionController::class, 'comment'])->name('comment.create')->middleware('auth:sanctum');
    Route::get('/close-open', [QuestionController::class, 'closeOpenQuestion'])->name('question.close');
    Route::post('/answer', [QuestionController::class, 'answerToQuestion'])->name('answer.create')->middleware('auth:sanctum');;
    Route::post('/', [QuestionController::class, 'show'])->name('questions.specific')->middleware('auth:sanctum');
    Route::post('/delete', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('/category',[QuestionController::class, 'fetchByCategory'])->name('filter.category');
    Route::post('/add-favourite',[QuestionController::class, 'favoriteQuestion'])->name('favourite.question')->middleware('auth:sanctum');
    Route::get('/readers',[QuestionController::class, 'questionReaders'])->name('question.readers');
});
Route::prefix('notifications')->group(function () {
    Route::get('/all', [NotificationController::class, 'index'])->name('notifications.all');
    Route::post('/add', [NotificationController::class, 'store'])->name('notifications.create');
    Route::post('/', [NotificationController::class, 'show'])->name('notifications.specific');
    Route::post('/update', [NotificationController::class, 'update'])->name('notifications.update');
    Route::post('/delete', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
Route::prefix('user-info')->group(function () {
    Route::get('/all', [UserInfoController::class, 'index'])->name('info.all');
    Route::post('/add', [UserInfoController::class, 'store'])->name('info.create');
    Route::post('/', [UserInfoController::class, 'show'])->name('info.specific');
    Route::post('/update', [UserInfoController::class, 'update'])->name('info.update');
    Route::post('/delete', [UserInfoController::class, 'destroy'])->name('info.delete');
});
Route::prefix('categories')->group(function () {
    Route::get('/all', [CategoryController::class, 'index'])->name('category.all');
    Route::post('/add', [CategoryController::class, 'store'])->name('category.create')->middleware('auth:sanctum');
    Route::post('/', [CategoryController::class, 'show'])->name('category.update');
    Route::post('/update', [CategoryController::class, 'update'])->name('category.update');
    Route::post('/delete', [CategoryController::class, 'destroy'])->name('category.delete');
});
Route::prefix('super-admin')->group(function () {
    Route::post('/add-admin', [AddAdminController::class ,'addAdmin'])->name('delete-user');
    Route::post('add-super-admin',[AddSuperAdminController::class,'addSuperAdmin']);
    Route::post('delete-admin',[DeleteAdminController::class,'deleteAdmin']);
    Route::post('delete-user',[DeleteUserController::class,'deleteUser']);
});

