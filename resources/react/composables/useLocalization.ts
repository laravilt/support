import { usePage } from '@inertiajs/react';
import { useCallback, useEffect, useMemo } from 'react';

export interface Localization {
    locale: string;
    timezone: string;
    direction: 'ltr' | 'rtl';
    isRtl: boolean;
}

export interface Translations {
    [key: string]: string | Translations;
}

type Replacements = Record<string, string | number>;

const defaultLocalization: Localization = {
    locale: 'en',
    timezone: 'UTC',
    direction: 'ltr',
    isRtl: false,
};

// Global translations cache
const translationsCache: Record<string, Translations> = {};

/**
 * Get value from translations object.
 * First tries direct key lookup (for flattened keys like 'laravilt-auth::auth.login.title'),
 * then falls back to nested object traversal using dot notation.
 */
function getNestedValue(obj: Translations, path: string): string | undefined {
    if (path in obj && typeof obj[path] === 'string') {
        return obj[path] as string;
    }

    const keys = path.split('.');
    let current: string | Translations | undefined = obj;

    for (const key of keys) {
        if (typeof current !== 'object' || current === null) {
            return undefined;
        }
        current = (current as Translations)[key];
    }

    return typeof current === 'string' ? current : undefined;
}

/**
 * Replace placeholders in translation string
 */
function replacePlaceholders(str: string, replacements: Replacements): string {
    return str.replace(/:(\w+)/g, (_, key: string) => {
        return String(replacements[key] ?? `:${key}`);
    });
}

/**
 * Hook for localization (React twin of the Vue useLocalization composable).
 */
export function useLocalization() {
    const page = usePage();
    const props = (page.props || {}) as Record<string, any>;

    const localization: Localization = props.localization ?? defaultLocalization;
    const { locale, timezone, direction, isRtl } = localization;

    // Update HTML attributes when localization changes
    useEffect(() => {
        if (typeof document !== 'undefined') {
            document.documentElement.lang = locale;
            document.documentElement.dir = direction;
        }
    }, [locale, direction]);

    const translations: Translations = useMemo(
        () => props.translations ?? translationsCache[locale] ?? {},
        [props.translations, locale],
    );

    /**
     * Translate a key with optional replacements
     * Similar to Laravel's __() / trans() function
     */
    const trans = useCallback(
        (key: string, replacements: Replacements = {}): string => {
            const value = getNestedValue(translations, key);

            if (value === undefined) {
                return replacePlaceholders(key, replacements);
            }

            return replacePlaceholders(value, replacements);
        },
        [translations],
    );

    const hasTranslation = useCallback(
        (key: string): boolean => getNestedValue(translations, key) !== undefined,
        [translations],
    );

    /**
     * Get plural translation, "one|many" format.
     * Similar to Laravel's trans_choice()
     */
    const transChoice = useCallback(
        (key: string, count: number, replacements: Replacements = {}): string => {
            const value = getNestedValue(translations, key);

            if (value === undefined) {
                return replacePlaceholders(key, { ...replacements, count });
            }

            const parts = value.split('|');
            const text = count === 1 ? parts[0] : (parts[1] ?? parts[0]);

            return replacePlaceholders(text, { ...replacements, count });
        },
        [translations],
    );

    return {
        locale,
        timezone,
        direction,
        isRtl,
        localization,
        translations,
        trans,
        __: trans,
        hasTranslation,
        transChoice,
    };
}

/**
 * Set translations cache (called from server)
 */
export function setTranslations(locale: string, translations: Translations): void {
    translationsCache[locale] = translations;
}

/**
 * Global trans function for use outside components
 */
export function trans(key: string, replacements: Replacements = {}): string {
    if (typeof window !== 'undefined' && (window as any).__inertia_page) {
        const pageData = (window as any).__inertia_page;
        const translations = pageData.props?.translations ?? {};
        const value = getNestedValue(translations, key);

        if (value !== undefined) {
            return replacePlaceholders(value, replacements);
        }
    }

    return replacePlaceholders(key, replacements);
}

export const __ = trans;
