<?php

if (! function_exists('t')) {
    function t(string $key, array $replace = [], ?string $locale = null): string
    {
        return __($key, $replace, $locale);
    }
}
