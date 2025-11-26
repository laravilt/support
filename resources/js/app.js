/**
 * Support Plugin for Vue.js
 *
 * This plugin can be registered in your main Laravilt application.
 *
 * Example usage in app.ts:
 *
 * import SupportPlugin from '@/plugins/support';
 *
 * app.use(SupportPlugin, {
 *     // Plugin options
 * });
 */

export default {
    install(app, options = {}) {
        // Plugin installation logic
        console.log('Support plugin installed', options);

        // Register global components
        // app.component('SupportComponent', ComponentName);

        // Provide global properties
        // app.config.globalProperties.$support = {};

        // Add global methods
        // app.mixin({});
    }
};
