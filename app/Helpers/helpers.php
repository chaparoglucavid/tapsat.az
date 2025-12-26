<?php

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
// function t_db(string $group, string $key): string
// {
//     $locale = Session::get('locale') ?? app()->getLocale();

//     return cache()->remember(
//         "db_translation_{$group}_{$key}_{$locale}",
//         3600,
//         function () use ($group, $key, $locale) {
//             return Translation::where('group', $group)
//                 ->where('key', $key)
//                 ->where('locale', $locale)
//                 ->value('value')
//                 ?? "{$group}.{$key}";
//         }
//     );
// }


if (! function_exists('menuActive')) {
    function menuActive(array|string $routes, string $class = 'active')
    {
        $routes = (array) $routes;

        foreach ($routes as $route) {
            if (Route::is($route)) {
                return $class;
            }
        }

        return '';
    }
}

if (! function_exists('menuOpen')) {
    function menuOpen(array|string $routes)
    {
        return menuActive($routes, 'open');
    }
}


function t_db(string $group, string $key): string
{
    $locale = session('locale', app()->getLocale());

    return cache()->remember(
        "db_translation_{$group}_{$key}_{$locale}",
        3600,
        function () use ($group, $key, $locale) {

            $translation = Translation::where([
                'group'  => $group,
                'key'    => $key,
                'locale' => $locale,
            ])->first();

            if ($translation) {
                return $translation->value;
            }

            $languages = Language::pluck('code');

            foreach ($languages as $code) {
                Translation::firstOrCreate(
                    [
                        'group'  => $group,
                        'key'    => $key,
                        'locale' => $code,
                    ],
                    [
                        'value' => ucfirst(str_replace('_', ' ', $key)),
                    ]
                );
            }

            return ucfirst(str_replace('_', ' ', $key));
        }
    );
}