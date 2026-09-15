<script setup>
const props = defineProps({
    href: {
        type: String,
        required: true
    },
    method: {
        type: String,
        default: 'GET'
    }
});

function handleClick(event) {
    // Leave modified and non-primary clicks (new tab, new window, download) to the browser
    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
    }

    // Leave downloads and links targeting another browsing context (forwarded attrs) to the browser
    const anchor = event.currentTarget;
    const target = anchor?.getAttribute('target');
    if (anchor?.hasAttribute('download') || (target && target !== '_self')) {
        return;
    }

    // Prevent default link behavior
    event.preventDefault();
    event.stopPropagation();

    console.log('[LaraviltLink] Click intercepted for:', props.href);

    // Use window.Laravilt.visit() for SPA navigation
    if (window.Laravilt && typeof window.Laravilt.visit === 'function') {
        console.log('[LaraviltLink] Using Laravilt.visit()');
        window.Laravilt.visit(props.href, props.method);
    } else {
        console.error('[LaraviltLink] window.Laravilt.visit not available!');
        console.log('[LaraviltLink] window.Laravilt:', window.Laravilt);
        console.warn('[LaraviltLink] Falling back to regular navigation');
        window.location.href = props.href;
    }
}
</script>

<template>
    <a
        :href="href"
        data-laravilt-link
        :data-laravilt-method="method"
        @click="handleClick"
    >
        <slot />
    </a>
</template>
