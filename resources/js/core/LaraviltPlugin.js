// Helper methods
import forOwn from "lodash-es/forOwn";
import has from "lodash-es/has";
import isObject from "lodash-es/isObject";

// Laravilt Components (currently implemented)
import ComponentRenderer from "../components/ComponentRenderer.vue";
import Link from "../components/Link.vue";
import Modal from "../components/Modal.vue";
import Render from "../components/Render.vue";
import ServerError from "../components/ServerError.vue";

// Laravilt Core app and progress bar
import { Laravilt } from "./Laravilt.js";
import { LaraviltProgress } from "./LaraviltProgress.js";

export default {
    /**
     * Registers all Laravilt components and makes the configured
     * options and the Laravilt core globally available.
     */
    install: (app, options) => {
        // Set the default options
        options = options || {};
        options.max_keep_alive = has(options, "max_keep_alive") ? options.max_keep_alive : 10;
        options.prefix = has(options, "prefix") ? options.prefix : "Laravilt";
        options.transform_anchors = has(options, "transform_anchors") ? options.transform_anchors : false;
        options.link_component = has(options, "link_component") ? options.link_component : "Link";
        options.progress_bar = has(options, "progress_bar") ? options.progress_bar : false;
        options.components = has(options, "components") ? options.components : {};
        options.view_transitions = has(options, "view_transitions") ? options.view_transitions : false;
        options.suppress_compile_errors = has(options, "suppress_compile_errors") ? options.suppress_compile_errors : false;

        // Prefixing Vue components
        const prefix = options.prefix;

        app
            .component(`${prefix}ComponentRenderer`, ComponentRenderer)
            .component(`${prefix}Link`, Link)
            .component(`${prefix}Modal`, Modal)
            .component(`${prefix}Render`, Render)
            .component(`${prefix}ServerError`, ServerError)
            .component(options.link_component, Link);

        // TODO: Add these components as they're implemented
        // .component(`${prefix}Confirm`, Confirm)
        // .component(`${prefix}Data`, Data)
        // .component(`${prefix}DataStores`, DataStores)
        // .component(`${prefix}Toast`, Toast)
        // .component(`${prefix}Toasts`, Toasts)
        // .component(`${prefix}Lazy`, Lazy)
        // .component(`${prefix}Rehydrate`, Rehydrate)
        // .component(`${prefix}Event`, Event)
        // .component(`${prefix}Flash`, Flash)
        // .component(`${prefix}Errors`, Errors)
        // .component(`${prefix}DynamicHtml`, DynamicHtml)
        // .component(`${prefix}PreloadedModal`, PreloadedModal)
        // .component(`${prefix}VueBridge`, VueBridge)
        // .component(`${prefix}State`, State)
        // .component(`${prefix}Transition`, Transition)
        // .component(`${prefix}Teleport`, Teleport)
        // .component(`${prefix}OnClickOutside`, OnClickOutside)
        // .component(`${prefix}Script`, Script)
        // .component(`${prefix}Defer`, Defer)
        // .component(`${prefix}Dialog`, Dialog)
        // .directive(`${prefix}PreserveScroll`, PreserveScrollDirective);

        // This way you can inject the global Laravilt instance and the options
        Object.defineProperty(app.config.globalProperties, "$laravilt", { get: () => Laravilt });
        Object.defineProperty(app.config.globalProperties, "$laraviltOptions", { get: () => Object.assign({}, { ...options }) });
        app.provide("$laravilt", app.config.globalProperties.$laravilt);
        app.provide("$laraviltOptions", app.config.globalProperties.$laraviltOptions);

        if (options.progress_bar) {
            // Set the default config
            const progressDefaults = {
                delay: 250,
                color: "#4B5563",
                css: true,
                spinner: false,
            };

            if(!isObject(options.progress_bar)) {
                options.progress_bar = {};
            }

            // Apply the any custom configuration to the default config
            ["delay", "color", "css", "spinner"].forEach((option) => {
                if(!has(options.progress_bar, option)) {
                    options.progress_bar[option] = progressDefaults[option];
                }
            });

            // Init the progress bar
            LaraviltProgress.init(options.progress_bar);
        }

        if(options.suppress_compile_errors) {
            app.config.compilerOptions.onError = (error) => {
                console.error({
                    message: error.message || "Unknown compiler error",
                    lineNumber: error.lineNumber,
                    compileError: error,
                });
            };
        }

        forOwn(options.components, (definition, name) => {
            app.component(name, definition);
        });
    }
};
