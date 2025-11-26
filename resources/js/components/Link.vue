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
