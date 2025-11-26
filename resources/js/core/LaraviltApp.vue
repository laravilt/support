<template>
  <div>
    <!-- main -->
    <KeepAlive
      v-if="!Laravilt.isSsr"
      :max="$laraviltOptions.max_keep_alive"
    >
      <Render
        :key="`visit.${Laravilt.pageVisitId.value}`"
        :style="backdropStyling"
        :html="html"
      />
    </KeepAlive>

    <Render
      v-else
      :key="`visit.${Laravilt.pageVisitId.value}`"
      :style="backdropStyling"
      :html="html"
    />

    <!-- confirm/toast components -->
    <Render :html="components" />

    <!-- modals -->
    <Render
      v-for="stack in Laravilt.currentStack.value"
      :key="`modal.${stack}`"
      :type="modals[stack].type"
      :html="modals[stack].html"
      :stack="stack"
      :on-top-of-stack="Laravilt.currentStack.value === stack"
      :animate="animateModal"
      @close="closeModal(stack)"
    />

    <!-- server errors -->
    <ServerError
      v-if="serverErrorHtml"
      :html="serverErrorHtml"
      @close="closeServerError"
    />
  </div>
</template>

<script setup>
import { ref, provide, nextTick, inject, computed, onMounted } from "vue";
import { Laravilt } from "./Laravilt.js";
import forOwn from "lodash-es/forOwn";
import isString from "lodash-es/isString";
import Render from "../components/Render.vue";
import ServerError from "../components/ServerError.vue";

/**
 * When the app runs in the browser, it will parse the initial data and components
 * from the div#app attributes. Otherwise, when running an SSR server, that
 * data will be passed as props and the 'el' prop will be empty.
 */
const props = defineProps({
    el: {
        type: [String, Object],
        required: false,
        default: "",
    },

    components: {
        type: String,
        required: false,
        default: (props) => {
            if(!Laravilt.isSsr) {
                const $el = isString(props.el) ? document.getElementById(props.el) : props.el;

                return JSON.parse($el.dataset.components) || "";
            }
        },
    },

    initialHtml: {
        type: String,
        required: false,
        default: (props) => {
            if(!Laravilt.isSsr) {
                const $el = isString(props.el) ? document.getElementById(props.el) : props.el;

                return JSON.parse($el.dataset.html) || "";
            }
        },
    },

    initialDynamics: {
        type: Object,
        required: false,
        default: (props) => {
            if(!Laravilt.isSsr) {
                const $el = isString(props.el) ? document.getElementById(props.el) : props.el;

                return JSON.parse($el.dataset.dynamics) || {};
            }
        },
    },

    initialLaraviltData: {
        type: Object,
        required: false,
        default: (props) => {
            if(!Laravilt.isSsr) {
                const $el = isString(props.el) ? document.getElementById(props.el) : props.el;

                return JSON.parse($el.dataset.laravilt) || {};
            }
        },
    },
});

/**
 * Provide the current stack to other components so they
 * can calculate whether they are on top of the stack.
 */
provide("stack", 0);

const html = ref();
const modals = ref([]);
const serverErrorHtml = ref(null);
const currentMeta = ref(null);
const animateModal = ref(true);
const $laraviltOptions = inject("$laraviltOptions") || {};

/**
 * When a modal or slideover is visible, it blurs the underlying page.
 */
const backdropStyling = computed(() => {
    if(Laravilt.currentStack.value < 1) {
        return [];
    }

    return {
        filter: "blur(4px)",
        "transition-property": "filter",
        "transition-duration": "150ms",
        "transition-timing-function": "cubic-bezier(0.4, 0, 0.2, 1)",
    };
});

/**
 * Hides the Server Error iframe.
 */
function closeServerError() {
    serverErrorHtml.value = null;
}

/**
 * Closes the modal in the given stack count.
 */
function closeModal(stack) {
    modals.value[stack] = null;
    Laravilt.popStack();
}

/**
 * It finds the meta tag with the given attribute and content. If it
 * doesn't exists, we create it and append it to the head.
 */
function insertMetaElement(meta)
{
    const $el = document.createElement("meta");

    forOwn(meta, (value, key) => {
        $el[key] = value;
    });

    document.getElementsByTagName("head")[0].appendChild($el);
}

/**
 * Removes the meta tag by the given meta object.
 */
function removeMetaElement(meta) {
    let selector = "meta";

    forOwn(meta, (content, attribute) => {
        selector = `${selector}[${attribute}="${content}"]`;
    });

    try {
        document.querySelector(selector)?.remove();
    } catch {
        //
    }
}

/**
 * Update the document title and meta tags based on the new head data.
 */
Laravilt.setOnHead((newHead) => {
    if(Laravilt.isSsr) {
        return;
    }

    if(currentMeta.value === null) {
        currentMeta.value = newHead.meta;
        return;
    }

    currentMeta.value.forEach((meta) => {
        removeMetaElement(meta);
    });

    currentMeta.value = newHead.meta;
    document.title = newHead.title;

    newHead.meta.forEach((meta) => {
        insertMetaElement(meta);
    });

    document.querySelector("link[rel=\"canonical\"]")?.remove();

    if(newHead.canonical) {
        const $el = document.createElement("link");
        $el.rel = "canonical";
        $el.href = newHead.canonical;

        document.getElementsByTagName("head")[0].appendChild($el);
    }
});

/**
 * Reset the modals array and set the new HTML. Scroll to the given
 * scroll height and, if configured, attach an click handler to
 * all anchor elements to use Laravilt's SPA capabilities.
 */

const onHtml = (newHtml, scrollY) => {
    modals.value = [];

    html.value = newHtml;

    nextTick(() => {
        if (!Laravilt.isSsr) {
            const hash = window.location.hash;

            if (hash) {
                const element = document.getElementById(hash.substring(1));

                if (element) {
                    // Set it explicitly so the browser scrolls to the element.
                    window.location.hash = hash;
                } else {
                    window.scrollTo(0, scrollY);
                }
            } else {
                window.scrollTo(0, scrollY);
            }
        }

        if ($laraviltOptions.transform_anchors) {
            [...document.querySelectorAll("a")].forEach((anchor) => {
                if (anchor.href == "" || anchor.href.charAt(0) == "#") {
                    return;
                }

                if (anchor.__vnode.dynamicProps !== null) {
                    return;
                }

                if (anchor.hasAttribute("download")) {
                    return;
                }

                anchor.onclick = function (event) {
                    event.preventDefault();
                    Laravilt.visit(anchor.href);
                };
            });
        }
    });
};

Laravilt.setOnHtml((newHtml, scrollY, animate) => {
    if (!Laravilt.isSsr && document.startViewTransition && $laraviltOptions.view_transitions && animate) {
        // With a transition:
        return document.startViewTransition(() => onHtml(newHtml, scrollY));
    }

    // SSR, no animation configuration, and fallback for unsupported browsers:
    onHtml(newHtml, scrollY, false);
});

/**
 * Push the modal HTML and type to the modals array.
 */
Laravilt.setOnModal(function (html, type) {
    if(modals.value[Laravilt.currentStack.value]) {
        animateModal.value = false;
    }

    modals.value[Laravilt.currentStack.value] = { html, type };

    nextTick(() => {
        animateModal.value = true;
    });
});
/**
 * Set the Server Error.
 */
Laravilt.setOnServerError(function (html) {
    serverErrorHtml.value = html;
});

/**
 * Initialize the Laravilt app with the data from the div#app attributes.
 */
Laravilt.init(props.initialHtml, props.initialDynamics, props.initialLaraviltData);

onMounted(() => {
    if(Laravilt.isSsr) {
        return;
    }

    const $el = isString(props.el) ? document.getElementById(props.el) : props.el;

    ["components", "html", "dynamics", "laravilt"].forEach((attribute) => {
        delete $el.dataset[attribute];
    });
});
</script>
