<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', function () {
    return view('upload');
})->name('upload.form');

Route::get('/sounds', function () {
    return view('sounds');
});

// Route::post('/api/sounds/upload', [FileUploadController::class, 'upload'])->name('file.upload');
// Route::post('/upload', [FileUploadController::class, 'upload'])->name('file.upload');



