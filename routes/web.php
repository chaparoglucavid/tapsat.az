<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{
    DashboardController,
    UploadFileController
};
use App\Http\Controllers\Admin\Configurations\LanguagesController;

Route::get('/', function () {
    notify()->success('Laravel Notify is awesome!');
    return redirect()->route('showLoginForm');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:web')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('change-language/{language}', [DashboardController::class, 'changeLanguage'])->name('change-language');

    //languages
    Route::resource('languages', LanguagesController::class);

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
