<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoundController;
use App\Http\Controllers\FileUploadController;


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

Route::post('/api/sounds/upload', [FileUploadController::class, 'upload'])->name('file.upload');

Route::get('/sounds', [SoundController::class, 'index'])->name('sounds.show');
Route::post('/sounds', [SoundController::class, 'store']);
Route::get('/sounds/{sound}', [SoundController::class, 'show']);
Route::delete('/sounds/{sound}', [FileUploadController::class, 'destroy'])->name('file.destroy');


