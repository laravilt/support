/**
 * Navigate to a URL using SPA navigation.
 *
 * @param {string} url - The URL to navigate to
 * @param {string} method - HTTP method (GET, POST, etc.)
 * @param {boolean} updateHistory - Whether to update browser history
 */
export async function navigate(url, method = 'GET', updateHistory = true) {
    try {
        const options = {
            method,
            headers: {
                'X-Laravilt': 'true',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        };

        const response = await fetch(url, options);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Update page content
        if (data.html) {
            const appElement = document.getElementById('laravilt-app');

            if (appElement) {
                appElement.innerHTML = data.html;

                // Re-mount components
                if (window.Laravilt) {
                    window.Laravilt.unmountAll();
                    window.Laravilt.mountComponents();
                }
            }
        }

        // Update URL in browser
        if (updateHistory) {
            history.pushState({ laravilt: true }, '', url);
        }

        // Dispatch navigation event
        window.dispatchEvent(new CustomEvent('laravilt:navigated', {
            detail: { url, method, data }
        }));

        // Scroll to top
        window.scrollTo(0, 0);

    } catch (error) {
        console.error('[Laravilt] Navigation failed:', error);

        // Fallback to regular navigation
        window.location.href = url;
    }
}

/**
 * Expose navigate function as window.Laravilt.visit() for global access
 */
if (typeof window !== 'undefined') {
    window.Laravilt = window.Laravilt || {};
    window.Laravilt.visit = navigate;
}
