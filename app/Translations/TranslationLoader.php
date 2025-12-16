<?php

namespace App\Translations;

use App\Models\Translations;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\LoaderInterface;

class TranslationLoader implements LoaderInterface
{
    public function load($locale, $group, $namespace = null)
    {
        if ($group === '*') {
            return [];
        }

        return Cache::tags(['translations', $locale])
            ->rememberForever("translations.{$locale}.{$group}", function () use ($locale, $group) {

                return Translations::where('locale', $locale)
                    ->where('group', $group)
                    ->pluck('value', 'key')
                    ->toArray();
            });
    }

    public function addNamespace($namespace, $hint) {}
    public function addJsonPath($path) {}
    public function namespaces() { return []; }
}
