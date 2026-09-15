import type { MouseEvent, ReactNode } from 'react';

export interface LinkProps {
    href: string;
    method?: string;
    children?: ReactNode;
}

/**
 * Anchor that routes through the legacy `window.Laravilt.visit()` SPA runtime when present,
 * falling back to a regular navigation.
 */
export default function Link({ href, method = 'GET', children }: LinkProps) {
    function handleClick(event: MouseEvent<HTMLAnchorElement>) {
        // Leave modified and non-primary clicks (new tab, new window, download) to the browser
        if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        // Prevent default link behavior
        event.preventDefault();
        event.stopPropagation();

        console.log('[LaraviltLink] Click intercepted for:', href);

        const laravilt = (window as any).Laravilt;

        // Use window.Laravilt.visit() for SPA navigation
        if (laravilt && typeof laravilt.visit === 'function') {
            console.log('[LaraviltLink] Using Laravilt.visit()');
            laravilt.visit(href, method);
        } else {
            console.error('[LaraviltLink] window.Laravilt.visit not available!');
            console.log('[LaraviltLink] window.Laravilt:', laravilt);
            console.warn('[LaraviltLink] Falling back to regular navigation');
            window.location.href = href;
        }
    }

    return (
        <a href={href} data-laravilt-link="" data-laravilt-method={method} onClick={handleClick}>
            {children}
        </a>
    );
}

export { Link };
