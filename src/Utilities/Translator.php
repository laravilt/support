<?php

namespace Laravilt\Support\Utilities;

/**
 * Translator Utility
 *
 * Provides translation helpers and RTL detection.
 */
class Translator
{
    /**
     * RTL locales.
     */
    protected static array $rtlLocales = [
        'ar', // Arabic
        'he', // Hebrew
        'fa', // Persian/Farsi
        'ur', // Urdu
        'yi', // Yiddish
        'ji', // Yiddish (alternative)
        'iw', // Hebrew (alternative)
    ];

    /**
     * Check if a locale is RTL.
     */
    public static function isRTL(?string $locale = null): bool
    {
        if ($locale === null) {
            // Try to get locale from app, fallback to 'en' if not available
            $app = app();
            if (method_exists($app, 'getLocale')) {
                $locale = $app->getLocale();
            } else {
                $locale = 'en';
            }
        }

        return in_array($locale, static::$rtlLocales);
    }

    /**
     * Get the text direction for a locale.
     *
     * @return string 'rtl' or 'ltr'
     */
    public static function direction(?string $locale = null): string
    {
        return static::isRTL($locale) ? 'rtl' : 'ltr';
    }

    /**
     * Get list of RTL locales.
     */
    public static function getRTLLocales(): array
    {
        return static::$rtlLocales;
    }

    /**
     * Add a locale to the RTL list.
     */
    public static function addRTLLocale(string $locale): void
    {
        if (! in_array($locale, static::$rtlLocales)) {
            static::$rtlLocales[] = $locale;
        }
    }
}
