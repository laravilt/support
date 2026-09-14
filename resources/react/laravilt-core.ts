/**
 * Laravilt Core (React)
 *
 * React twin of laravilt-core.js. Only the pieces that other packages actually use are ported:
 * the components and the composables. The legacy HTML-over-the-wire runtime (core/Laravilt.js,
 * LaraviltApp, LaraviltProgress, mixins/, utils/) is not ported — Inertia replaces it.
 */
import type { ComponentType } from 'react';
import ComponentRenderer from './components/ComponentRenderer';
import Link from './components/Link';
import Modal from './components/Modal';
import Render from './components/Render';
import ServerError from './components/ServerError';
import { registerComponents } from './composables/registry';

// Components
export { ComponentRenderer, Link, Modal, Render, ServerError };

// Composables
export { useLocalization, trans, __, setTranslations } from './composables/useLocalization';

export interface LaraviltCoreOptions {
    /** Prefix for the registered component names (default `Laravilt`). */
    prefix?: string;
    /** Extra name the Link component is registered under (default `Link`). */
    link_component?: string;
    /** Additional components to register, keyed by name. */
    components?: Record<string, ComponentType<any>>;
}

/**
 * Registers the same component names LaraviltPlugin.install() registers in Vue
 * (`LaraviltComponentRenderer`, `LaraviltLink`, `LaraviltModal`, `LaraviltRender`, `LaraviltServerError`, `Link`).
 */
export function register(options: LaraviltCoreOptions = {}): void {
    const prefix = 'prefix' in options ? options.prefix : 'Laravilt';
    const linkComponent = 'link_component' in options ? options.link_component : 'Link';

    registerComponents({
        [`${prefix}ComponentRenderer`]: ComponentRenderer,
        [`${prefix}Link`]: Link,
        [`${prefix}Modal`]: Modal,
        [`${prefix}Render`]: Render,
        [`${prefix}ServerError`]: ServerError,
        [`${linkComponent}`]: Link,
    });

    registerComponents(options.components ?? {});
}

export default { register };
