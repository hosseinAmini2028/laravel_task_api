<?php

use App\Http\Controllers\Auth\Authcontroller;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\Tasks\UserTaskController;
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

Route::prefix('auth')->group(function () {
    Route::post('register', [Authcontroller::class, 'register']);
    Route::post('login', [Authcontroller::class, 'login']);
    Route::delete('logout', [Authcontroller::class, 'logout'])->middleware(['auth']);
});


Route::prefix('tasks')->middleware(['auth'])->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::post('update/{task}', [TaskController::class, 'update']);
    Route::get('/{task}', [TaskController::class, 'show']);
    Route::delete('/{task}', [TaskController::class, 'destroy']);
});
