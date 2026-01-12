<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\Translation;
use App\Observers\TranslationObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Translations\TranslationLoader;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('languages')) {
            $languages = Language::all();
            View::share(['languages' => $languages]);
        }
    }
}
