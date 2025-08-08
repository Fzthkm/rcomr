<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ApplicationController::class)->group(function () {
    Route::get('/applications', 'index')->name('applications.index');
    Route::get('/applications/create', 'create')->name('applications.create');
    Route::post('/applications', 'store')->name('applications.store');
});
