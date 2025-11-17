<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="isOpen"
                class="laravilt-modal-backdrop"
                @click="handleBackdropClick"
            >
                <div
                    class="laravilt-modal-dialog"
                    @click.stop
                >
                    <div v-if="title" class="laravilt-modal-header">
                        <h3>{{ title }}</h3>
                        <button
                            class="laravilt-modal-close"
                            @click="close"
                        >
                            &times;
                        </button>
                    </div>
                    <div class="laravilt-modal-body">
                        <slot />
                    </div>
                    <div v-if="$slots.footer" class="laravilt-modal-footer">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: null
    },
    closeOnBackdrop: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:open', 'close']);

const isOpen = ref(props.open);

watch(() => props.open, (newValue) => {
    isOpen.value = newValue;
});

function close() {
    isOpen.value = false;
    emit('update:open', false);
    emit('close');
}

function handleBackdropClick() {
    if (props.closeOnBackdrop) {
        close();
    }
}
</script>

<style>
.laravilt-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.laravilt-modal-dialog {
    background: white;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow: auto;
}

.laravilt-modal-header {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.laravilt-modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.laravilt-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    opacity: 0.5;
}

.laravilt-modal-close:hover {
    opacity: 1;
}

.laravilt-modal-body {
    padding: 1rem;
}

.laravilt-modal-footer {
    padding: 1rem;
    border-top: 1px solid #e5e7eb;
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
