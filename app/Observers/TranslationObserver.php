<?php

namespace App\Observers;

use App\Models\Translations;
use Illuminate\Support\Facades\Cache;

class TranslationObserver
{
    public function saved(Translations $translation): void
    {
        Cache::tags(['translations', $translation->locale])->flush();
    }

    public function deleted(Translations $translation): void
    {
        Cache::tags(['translations', $translation->locale])->flush();
    }
}
