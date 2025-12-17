<?php

use App\Models\Translation;
use Illuminate\Support\Facades\Session;
function t_db(string $group, string $key): string
{
    $locale = Session::get('locale') ?? app()->getLocale();

    return cache()->remember(
        "db_translation_{$group}_{$key}_{$locale}",
        3600,
        function () use ($group, $key, $locale) {
            return Translation::where('group', $group)
                ->where('key', $key)
                ->where('locale', $locale)
                ->value('value')
                ?? "{$group}.{$key}";
        }
    );
}
