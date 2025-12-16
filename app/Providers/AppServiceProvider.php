<?php

namespace App\Providers;

use App\Models\Languages;
use App\Models\Translations;
use App\Observers\TranslationObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Translations\TranslationLoader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('translation.loader', function () {
            return new TranslationLoader();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $locale = Cache::rememberForever('default_locale', function () {
            return Languages::where('is_default', true)->value('code') ?? 'en';
        });

        app()->setLocale($locale);
        Translations::observe(TranslationObserver::class);
    }
}
