import type { ComponentType } from 'react';

/**
 * Global component registry — the React replacement for Vue's
 * `app.component('laravilt-text-input', TextInput)` registrations.
 * Keys use the same names as the Vue packages register.
 */
const REGISTRY_KEY = '__laravilt_react_components__';

type Registry = Record<string, ComponentType<any>>;

function registry(): Registry {
    const holder = (typeof window !== 'undefined' ? window : globalThis) as any;

    if (!holder[REGISTRY_KEY]) {
        holder[REGISTRY_KEY] = {};
    }

    return holder[REGISTRY_KEY] as Registry;
}

export function registerComponent(name: string, component: ComponentType<any>): void {
    registry()[name] = component;
}

export function registerComponents(components: Registry): void {
    Object.entries(components).forEach(([name, component]) => registerComponent(name, component));
}

export function resolveComponent(name: string | null | undefined): ComponentType<any> | null {
    if (!name) {
        return null;
    }

    const all = registry();

    return all[name] ?? all[`laravilt-${name.replace(/_/g, '-')}`] ?? null;
}

export function registeredComponents(): Readonly<Registry> {
    return registry();
}
