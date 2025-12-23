<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{
    DashboardController,
    UploadFileController
};
use App\Http\Controllers\Admin\Configurations\{
    LanguagesController,
    TranslationsController,
};

use App\Http\Controllers\Admin\Addresses\{
    CitiesController
};

Route::get('/', function () {
    notify()->success('Laravel Notify is awesome!');
    return redirect()->route('showLoginForm');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:web')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('change-language/{language}', [DashboardController::class, 'changeLanguage'])->name('change-language');

    //cities
    Route::resource('cities', CitiesController::class);

    //languages
    Route::resource('languages', LanguagesController::class);
    Route::post('languages/{language}/set-default', [LanguagesController::class, 'setDefault'])
        ->name('languages.set-default');
    Route::get('languages/{uid}/translations', [LanguagesController::class, 'translations'])
        ->name('languages.translations');

    //translations
    Route::post('translations/{translation}', [TranslationsController::class, 'update'])
        ->name('translations.update');
    Route::post('translations/update-all/{language}', [TranslationsController::class, 'updateAll'])
        ->name('translations.update-all');

    Route::post('clear-cache', [DashboardController::class, 'clearCache'])->name('clear-cache');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
