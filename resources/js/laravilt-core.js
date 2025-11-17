/**
 * Laravilt Core
 *
 * Complete SPA framework for Laravel applications.
 * Ported from Splade with full feature parity.
 */

// Core
export { Laravilt } from "./core/Laravilt.js";
export { LaraviltProgress } from "./core/LaraviltProgress.js";
export { default as LaraviltPlugin } from "./core/LaraviltPlugin.js";
export { default as LaraviltApp } from "./core/LaraviltApp.vue";

// Components
export { default as ComponentRenderer } from "./components/ComponentRenderer.vue";
export { default as Link } from "./components/Link.vue";
export { default as Modal } from "./components/Modal.vue";
export { default as Render } from "./components/Render.vue";
export { default as ServerError } from "./components/ServerError.vue";

// Utilities
export { default as EventBus } from "./utils/event-bus.js";
export { navigate } from "./utils/navigate.js";

// Mixins
export { default as componentMixin } from "./mixins/component-mixin.js";

// Default export is the plugin
export { default } from "./core/LaraviltPlugin.js";
