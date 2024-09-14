<?php

use Illuminate\Support\Facades\Cache;
use Domain\Translation\Actions\Cache\ClearLocalizedCacheKey;

test('clear-localized-cache-key', function () {
    $key = 'test-key';
    $locales = config('localized-routes.supported_locales');

    Cache::remember($key, now()->addMinutes(5), fn () => 'test-value');
    foreach ($locales as $locale) {
        $localizedKey = "{$key}.{$locale}";
        Cache::remember($localizedKey, now()->addMinutes(5), fn () => 'test-value');
    }

    ClearLocalizedCacheKey::run($key);

    expect(cache()->has($key))->toBeFalse();
    foreach ($locales as $locale) {
        $localizedKey = "{$key}.{$locale}";
        expect(cache()->has($localizedKey))->toBeFalse();
    }
});
