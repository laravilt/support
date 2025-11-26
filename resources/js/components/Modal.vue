<script setup lang="ts">
import { computed, type Component } from 'vue';
import * as LucideIcons from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

const props = defineProps<{
    open: boolean;
    title?: string;
    description?: string;
    closeOnBackdrop?: boolean;
    icon?: string;
    iconColor?: string;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    'close': [];
}>();

const isOpen = computed({
    get: () => props.open,
    set: (value) => {
        emit('update:open', value);
        if (!value) {
            emit('close');
        }
    },
});

// Get Lucide icon component from icon name
const iconComponent = computed<Component | null>(() => {
    if (!props.icon) return null;

    // If it starts with 'heroicon-o-', map it to Lucide
    if (props.icon.startsWith('heroicon-o-')) {
        const iconMap: Record<string, string> = {
            'heroicon-o-bolt': 'Zap',
            'heroicon-o-star': 'Star',
            'heroicon-o-exclamation-triangle': 'AlertTriangle',
            'heroicon-o-document-text': 'FileText',
            'heroicon-o-trash': 'Trash2',
            'heroicon-o-information-circle': 'Info',
            'heroicon-o-squares-2x2': 'LayoutGrid',
            'heroicon-o-x-mark': 'X',
            'heroicon-o-beaker': 'Flask',
            'heroicon-o-home': 'Home',
            'heroicon-o-user': 'User',
            'heroicon-o-users': 'Users',
            'heroicon-o-cog': 'Settings',
            'heroicon-o-chart-bar': 'BarChart',
            'heroicon-o-folder': 'Folder',
            'heroicon-o-shopping-cart': 'ShoppingCart',
        };

        const lucideName = iconMap[props.icon] || 'Circle';
        return (LucideIcons as any)[lucideName] || LucideIcons.Circle;
    }

    // Try to use it as a Lucide icon name directly
    return (LucideIcons as any)[props.icon] || LucideIcons.Circle;
});

// Get icon color classes
const iconColorClasses = computed(() => {
    const colorMap: Record<string, string> = {
        'danger': 'text-red-500',
        'warning': 'text-yellow-500',
        'success': 'text-green-500',
        'info': 'text-blue-500',
        'primary': 'text-primary',
    };

    return props.iconColor ? colorMap[props.iconColor] || 'text-muted-foreground' : 'text-muted-foreground';
});
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent>
            <DialogHeader v-if="title || description || iconComponent">
                <div v-if="iconComponent" class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted mb-4">
                    <component :is="iconComponent" :class="['h-6 w-6', iconColorClasses]" />
                </div>
                <DialogTitle v-if="title">{{ title }}</DialogTitle>
                <DialogDescription v-if="description">{{ description }}</DialogDescription>
            </DialogHeader>

            <slot />

            <DialogFooter v-if="$slots.footer">
                <slot name="footer" />
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
