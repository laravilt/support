<template>
    <a
        :href="href"
        data-laravilt-link
        :data-laravilt-method="method"
        @click.prevent="handleClick"
    >
        <slot />
    </a>
</template>

<script setup>
import { navigate } from '../utils/navigate';

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

function handleClick() {
    navigate(props.href, props.method);
}
</script>
