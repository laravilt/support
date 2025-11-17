<template>
    <div
        :data-laravilt-component="componentName"
        :data-laravilt-key="componentKey"
        v-html="innerHTML"
    />
</template>

<script setup>
const props = defineProps({
    componentName: {
        type: String,
        required: true
    },
    componentProps: {
        type: Object,
        default: () => ({})
    },
    componentKey: {
        type: String,
        required: true
    },
    innerHTML: {
        type: String,
        default: ''
    }
});
</script>
