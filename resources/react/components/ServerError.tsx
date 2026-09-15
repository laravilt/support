import { useEffect, useRef } from 'react';
import { useLatest } from '../composables/hooks';

export interface ServerErrorProps {
    html: string;
    /** Vue `close` event. */
    onClose?: () => void;
}

/**
 * Full-screen overlay with an iframe holding the server's error HTML.
 */
export default function ServerError({ html, onClose }: ServerErrorProps) {
    const iframeElement = useRef<HTMLIFrameElement>(null);
    const latestOnClose = useLatest(onClose);
    const listenerRef = useRef<((event: KeyboardEvent) => void) | null>(null);
    // The body overflow in place before the overlay locked scrolling.
    const previousOverflow = useRef('');

    /**
     * Restore the body overflow style and emit the 'close' event.
     */
    const hide = () => {
        document.body.style.overflow = previousOverflow.current;

        if (listenerRef.current) {
            document.removeEventListener('keydown', listenerRef.current);
        }

        latestOnClose.current?.();
    };

    const latestHide = useLatest(hide);

    /**
     * Creates a new HTML element with an iframe holding the HTML from the props.
     */
    useEffect(() => {
        const page = document.createElement('html');
        page.innerHTML = html;
        page.querySelectorAll('a').forEach((a) => a.setAttribute('target', '_top'));

        previousOverflow.current = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        const iframe = iframeElement.current;

        if (!iframe || !iframe.contentWindow) {
            throw new Error('iframe not yet ready.');
        }

        iframe.contentWindow.document.open();
        iframe.contentWindow.document.write(page.outerHTML);
        iframe.contentWindow.document.close();

        /**
         * Calls the hide() event when the keycode is escape.
         */
        const keyDownListener = (event: KeyboardEvent) => {
            if (event.keyCode === 27) {
                latestHide.current();
            }
        };

        listenerRef.current = keyDownListener;
        document.addEventListener('keydown', keyDownListener);

        return () => {
            document.removeEventListener('keydown', keyDownListener);
            // Don't leave the page scroll-locked when unmounted without hide()
            document.body.style.overflow = previousOverflow.current;
        };
        // The Vue component only builds the iframe on mount.
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    return (
        <div
            style={{
                position: 'fixed',
                top: '0px',
                right: '0px',
                bottom: '0px',
                left: '0px',
                zIndex: 200000,
                boxSizing: 'border-box',
                height: '100vh',
                width: '100vw',
                backgroundColor: 'rgb(0 0 0 / 0.75)',
                padding: '2rem',
            }}
            onClick={hide}
        >
            <iframe ref={iframeElement} className="bg-white w-full h-full" />
        </div>
    );
}

export { ServerError };
