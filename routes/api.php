<?php

use App\Http\Controllers\{LabelController, TaskController, TodoListController};
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Service\GoogleServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-lists', TodoListController::class);
    Route::apiResource('todo-lists.tasks', TaskController::class)->except('show')->shallow();
    Route::apiResource('labels', LabelController::class);

    Route::get('service/google/connect', [GoogleServiceController::class, 'connect'])->name('services.google.connect');
    Route::post('service/google/callback', [GoogleServiceController::class, 'callback'])->name('services.google.callback');
    Route::post('service/google/upload/{token}', [GoogleServiceController::class, 'upload'])->name('services.google.upload');
});
