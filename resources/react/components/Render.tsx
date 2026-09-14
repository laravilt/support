import { useEffect } from 'react';
import { useLatest } from '../composables/hooks';

export interface RenderProps {
    html?: string;
    /**
     * In Vue this object became the `data()` of the runtime-compiled template.
     * React cannot compile templates at runtime, so it is accepted for API parity but unused.
     */
    passthrough?: Record<string, any>;
    /** Called after every render of new HTML (in addition to the global `rendered` event). */
    onRendered?: () => void;
}

/**
 * Emit the `rendered` event the same way the Vue `Laravilt.emit('rendered')` does:
 * through the legacy runtime's event bus when it is loaded (it also dispatches the DOM event),
 * otherwise as a `laravilt:rendered` CustomEvent on `document`.
 */
function emitRendered(): void {
    if (typeof document === 'undefined') {
        return;
    }

    const laravilt = (window as any).Laravilt;

    if (laravilt && typeof laravilt.emit === 'function') {
        try {
            laravilt.emit('rendered');

            return;
        } catch {
            // The runtime has no event bus for the current page — fall through to the DOM event.
        }
    }

    document.dispatchEvent(new CustomEvent('laravilt:rendered', { detail: {} }));
}

/**
 * React twin of Render.vue.
 *
 * LIMITATION: the Vue component compiled `html` as a Vue template at runtime (so it could contain
 * Vue components, directives and `{{ }}` bindings against `passthrough`). React has no runtime
 * template compiler, so the HTML is injected verbatim with dangerouslySetInnerHTML — interactive
 * components and bindings inside it are NOT hydrated. The `rendered` event is still emitted after
 * each HTML change.
 */
export default function Render({ html = '', onRendered }: RenderProps) {
    const latestOnRendered = useLatest(onRendered);

    // Vue: watch(() => props.html, updateRender, { immediate: true }) + nextTick(() => emit('rendered'))
    useEffect(() => {
        emitRendered();
        latestOnRendered.current?.();
    }, [html, latestOnRendered]);

    if (!html) {
        return null;
    }

    return <div style={{ display: 'contents' }} dangerouslySetInnerHTML={{ __html: html }} />;
}

export { Render };
