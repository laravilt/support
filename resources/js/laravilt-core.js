import { createApp } from 'vue';
import Link from './components/Link.vue';
import Modal from './components/Modal.vue';
import ComponentRenderer from './components/ComponentRenderer.vue';
import { navigate } from './utils/navigate';
import EventBus from './utils/event-bus';

/**
 * Laravilt Core JavaScript
 *
 * Handles SPA navigation, component mounting, and event management.
 */
class LaraviltCore {
    constructor() {
        this.app = null;
        this.eventBus = new EventBus();
        this.components = new Map();
    }

    /**
     * Initialize Laravilt Core.
     */
    init() {
        this.mountComponents();
        this.initializeNavigation();
        this.initializeModals();
    }

    /**
     * Mount all Laravilt components on the page.
     */
    mountComponents() {
        const elements = document.querySelectorAll('[data-laravilt-component]');

        elements.forEach(element => {
            const name = element.dataset.laraviltComponent;
            const props = JSON.parse(element.dataset.laraviltProps || '{}');
            const key = element.dataset.laraviltKey;

            // Create Vue app instance for this component
            const app = createApp(ComponentRenderer, {
                componentName: name,
                componentProps: props,
                componentKey: key,
                innerHTML: element.innerHTML
            });

            // Register global components
            app.component('LaraviltLink', Link);
            app.component('LaraviltModal', Modal);

            // Mount and store reference
            app.mount(element);
            this.components.set(key, app);
        });
    }

    /**
     * Initialize SPA navigation.
     */
    initializeNavigation() {
        // Intercept clicks on Laravilt links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('[data-laravilt-link]');
            if (link) {
                e.preventDefault();
                navigate(link.href, link.dataset.laraviltMethod || 'GET');
            }
        });

        // Handle browser back/forward
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.laravilt) {
                navigate(window.location.href, 'GET', false);
            }
        });
    }

    /**
     * Initialize modal functionality.
     */
    initializeModals() {
        this.eventBus.on('modal:open', (modalName) => {
            const modal = document.querySelector(`[data-laravilt-modal="${modalName}"]`);
            if (modal) {
                modal.setAttribute('data-laravilt-open', 'true');
            }
        });

        this.eventBus.on('modal:close', (modalName) => {
            const modal = document.querySelector(`[data-laravilt-modal="${modalName}"]`);
            if (modal) {
                modal.setAttribute('data-laravilt-open', 'false');
            }
        });
    }

    /**
     * Unmount all components (for cleanup).
     */
    unmountAll() {
        this.components.forEach(app => app.unmount());
        this.components.clear();
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.Laravilt = new LaraviltCore();
    window.Laravilt.init();
});

// Export for use as Vue plugin
export default {
    install(app) {
        app.component('LaraviltLink', Link);
        app.component('LaraviltModal', Modal);
        app.component('LaraviltComponentRenderer', ComponentRenderer);
    }
};
