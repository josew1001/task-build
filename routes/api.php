<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/task', [TasksController::class, 'store']);
Route::get('/task', [TasksController::class, 'index']);

Route::post('/taskDescription', [CommentsController::class, 'store']);
Route::get('/taskDescription', [CommentsController::class, 'index']);