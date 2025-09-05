<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ApplicationController,
    SpecialistController,
    SpecializationController
};

/*
|--------------------------------------------------------------------------
| Глобальные ограничения параметров
|--------------------------------------------------------------------------
| ВАЖНО: паттерны должны идти ДО определения маршрутов.
*/
Route::pattern('specialist', '[0-9]+');
Route::pattern('specialization', '[0-9]+');
Route::pattern('application', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Главная
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Applications
|--------------------------------------------------------------------------
*/
Route::resource('applications', ApplicationController::class);

Route::patch('applications/reassign', [ApplicationController::class, 'reassign'])
    ->name('applications.reassign');

/*
|--------------------------------------------------------------------------
| Specialists
|--------------------------------------------------------------------------
*/
Route::resource('specialists', SpecialistController::class);

// Проверка возможности удаления (409 + список заявок, если нельзя)
Route::get('specialists/{specialist}/deletion-check', [SpecialistController::class, 'deletionCheck'])
    ->name('specialists.deletion-check');
Route::get('specialists/{specialist}/applications', [ApplicationController::class, 'bySpecialist'])
    ->name('applications.by-specialist');

/*
|--------------------------------------------------------------------------
| Specializations
|--------------------------------------------------------------------------
*/
Route::resource('specializations', SpecializationController::class);

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
| Если очень хочется, делаем умный fallback: JSON для AJAX, редирект для обычной навигации.
*/
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['message' => 'Not Found'], 404);
    }
    return redirect()->route('home');
});
