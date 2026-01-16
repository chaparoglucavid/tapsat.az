<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{
    DashboardController,
};
use App\Http\Controllers\Admin\Configurations\{
    LanguagesController,
    TranslationsController,
};

use App\Http\Controllers\Admin\Addresses\{
    CitiesController,
    RegionsController
};

use App\Http\Controllers\Admin\DataStructure\{
    CategoriesController,
    CategoryPackagePricesController,
    ComplaintSubjectsController
};

use App\Http\Controllers\Admin\Packages\{
    PackagesController,
};

use App\Http\Controllers\Admin\Announcements\{
    AnnouncementsController,
    AnnouncementComplaintsController,
    ComplaintsAdminController
};
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\UserCreditCardsController;
use App\Http\Controllers\Admin\Security\{
    SuspiciousActivitiesController,
    IpRulesController
};
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\Stores\StoreController;

use App\Http\Controllers\Admin\Notifications\PushNotificationController;

Route::get('/', function () {
    notify()->success('Laravel Notify is awesome!');
    return redirect()->route('showLoginForm');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:web')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('change-language', [DashboardController::class, 'changeLanguage'])->name('change-language');

    //cities
    Route::resource('cities', CitiesController::class);
    //regions
    Route::resource('regions', RegionsController::class);
    //categories
    Route::resource('categories', CategoriesController::class);
    //complaint-subjects
    Route::resource('complaint-subjects', ComplaintSubjectsController::class)->except(['show']);

    //category-package-prices
    Route::resource('category-package-prices', CategoryPackagePricesController::class)->only(['index', 'update']);

    //media
    Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('media/revert', [MediaController::class, 'revert'])->name('media.revert');

    //announcements
    Route::resource('announcements', AnnouncementsController::class);
    Route::post('announcements/{uuid}/complaints', [AnnouncementComplaintsController::class, 'store'])->name('announcements.complaints.store');
    Route::get('announcements-complaints', [ComplaintsAdminController::class, 'index'])->name('announcements.complaints.index');

    //users
    Route::resource('users', UsersController::class);

    //stores
    Route::resource('stores', StoreController::class);
    Route::post('stores/{uuid}/status', [StoreController::class, 'updateStatus'])->name('stores.update-status');
    
    //user credit cards (nested or standalone, maybe standalone for now but linked)
    Route::resource('user-credit-cards', UserCreditCardsController::class);

    //packages
    Route::resource('packages', PackagesController::class);

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

    //security
    Route::get('security/suspicious-activities', [SuspiciousActivitiesController::class, 'index'])
        ->name('security.suspicious-activities.index');
    Route::resource('ip-rules', IpRulesController::class)->except(['show']);

    //notifications
    Route::resource('push-notifications', PushNotificationController::class);
    Route::post('push-notifications/{uuid}/send', [PushNotificationController::class, 'send'])->name('push-notifications.send');

    Route::post('clear-cache', [DashboardController::class, 'clearCache'])->name('clear-cache');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
