import { useCallback, useEffect, useRef, useState, type RefObject } from 'react';

/**
 * Keep a ref pointing at the latest value, for async callbacks that must not close over stale state.
 */
export function useLatest<T>(value: T): RefObject<T> {
    const ref = useRef(value);
    ref.current = value;

    return ref;
}

/**
 * Debounced callback (replacement for @vueuse `useDebounceFn`). The returned function is stable.
 */
export function useDebouncedCallback<Args extends any[]>(
    callback: (...args: Args) => void,
    delay: number,
): ((...args: Args) => void) & { cancel: () => void } {
    const latest = useLatest(callback);
    const timeout = useRef<ReturnType<typeof setTimeout> | null>(null);

    useEffect(
        () => () => {
            if (timeout.current) {
                clearTimeout(timeout.current);
            }
        },
        [],
    );

    const debounced = useCallback(
        (...args: Args) => {
            if (timeout.current) {
                clearTimeout(timeout.current);
            }

            timeout.current = setTimeout(() => latest.current(...args), delay);
        },
        [delay, latest],
    ) as ((...args: Args) => void) & { cancel: () => void };

    debounced.cancel = () => {
        if (timeout.current) {
            clearTimeout(timeout.current);
        }
    };

    return debounced;
}

/**
 * State persisted in localStorage (replacement for @vueuse `useLocalStorage`).
 */
export function useLocalStorage<T>(key: string, initialValue: T): [T, (value: T | ((previous: T) => T)) => void] {
    const [value, setValue] = useState<T>(() => {
        if (typeof window === 'undefined') {
            return initialValue;
        }

        try {
            const stored = window.localStorage.getItem(key);

            return stored === null ? initialValue : (JSON.parse(stored) as T);
        } catch {
            return initialValue;
        }
    });

    const update = useCallback(
        (next: T | ((previous: T) => T)) => {
            setValue((previous) => {
                const resolved = typeof next === 'function' ? (next as (previous: T) => T)(previous) : next;

                if (typeof window !== 'undefined') {
                    window.localStorage.setItem(key, JSON.stringify(resolved));
                }

                return resolved;
            });
        },
        [key],
    );

    return [value, update];
}

/**
 * Attach an event listener to window/document/an element for the component lifetime.
 */
export function useEventListener<K extends keyof WindowEventMap>(
    event: K | string,
    handler: (event: any) => void,
    target?: EventTarget | null | RefObject<EventTarget | null>,
): void {
    const latest = useLatest(handler);

    useEffect(() => {
        const resolved =
            target && 'current' in (target as any)
                ? (target as RefObject<EventTarget | null>).current
                : ((target as EventTarget | null | undefined) ?? (typeof window !== 'undefined' ? window : null));

        if (!resolved) {
            return;
        }

        const listener = (e: Event) => latest.current(e);
        resolved.addEventListener(event, listener);

        return () => resolved.removeEventListener(event, listener);
    }, [event, target, latest]);
}

/**
 * Call handler when a pointer-down happens outside the referenced element (replacement for @vueuse `onClickOutside`).
 */
export function useOnClickOutside(
    ref: RefObject<HTMLElement | null>,
    handler: (event: PointerEvent | MouseEvent | TouchEvent) => void,
    enabled = true,
): void {
    const latest = useLatest(handler);

    useEffect(() => {
        if (!enabled || typeof document === 'undefined') {
            return;
        }

        const listener = (event: PointerEvent | MouseEvent | TouchEvent) => {
            const element = ref.current;

            if (!element || element.contains(event.target as Node)) {
                return;
            }

            latest.current(event);
        };

        document.addEventListener('pointerdown', listener);

        return () => document.removeEventListener('pointerdown', listener);
    }, [ref, enabled, latest]);
}

/**
 * Reactive media query (replacement for @vueuse `useMediaQuery`).
 */
export function useMediaQuery(query: string): boolean {
    const [matches, setMatches] = useState<boolean>(() =>
        typeof window !== 'undefined' ? window.matchMedia(query).matches : false,
    );

    useEffect(() => {
        if (typeof window === 'undefined') {
            return;
        }

        const media = window.matchMedia(query);
        const listener = () => setMatches(media.matches);

        listener();
        media.addEventListener('change', listener);

        return () => media.removeEventListener('change', listener);
    }, [query]);

    return matches;
}
