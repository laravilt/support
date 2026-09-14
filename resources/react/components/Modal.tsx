import * as LucideIcons from 'lucide-react';
import type { ComponentType, ReactNode } from 'react';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { cn } from '@/lib/utils';

export interface ModalProps {
    open: boolean;
    title?: string;
    description?: string;
    closeOnBackdrop?: boolean;
    icon?: string;
    iconColor?: string;
    /** Vue `update:open` event. */
    onUpdateOpen?: (value: boolean) => void;
    /** Vue `close` event — fired whenever the dialog is closed. */
    onClose?: () => void;
    children?: ReactNode;
    /** Vue named slot `footer`. */
    footer?: ReactNode;
}

const heroiconMap: Record<string, string> = {
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

const iconColorMap: Record<string, string> = {
    danger: 'text-red-500',
    warning: 'text-yellow-500',
    success: 'text-green-500',
    info: 'text-blue-500',
    primary: 'text-primary',
};

/**
 * Same lookup as the Vue component: exact lucide export name (heroicon-o-* mapped first), falling back to Circle.
 */
function getIconComponent(icon?: string): ComponentType<{ className?: string }> | null {
    if (!icon) {
        return null;
    }

    const all = LucideIcons as unknown as Record<string, ComponentType<{ className?: string }>>;

    if (icon.startsWith('heroicon-o-')) {
        const lucideName = heroiconMap[icon] || 'Circle';

        return all[lucideName] || LucideIcons.Circle;
    }

    return all[icon] || LucideIcons.Circle;
}

export default function Modal({ open, title, description, icon, iconColor, onUpdateOpen, onClose, children, footer }: ModalProps) {
    const IconComponent = getIconComponent(icon);

    const iconColorClasses = iconColor ? iconColorMap[iconColor] || 'text-muted-foreground' : 'text-muted-foreground';

    const handleOpenChange = (value: boolean) => {
        onUpdateOpen?.(value);

        if (!value) {
            onClose?.();
        }
    };

    return (
        <Dialog open={open} onOpenChange={handleOpenChange}>
            <DialogContent>
                {(title || description || IconComponent) && (
                    <DialogHeader>
                        {IconComponent && (
                            <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted mb-4">
                                <IconComponent className={cn('h-6 w-6', iconColorClasses)} />
                            </div>
                        )}
                        {title && <DialogTitle>{title}</DialogTitle>}
                        {description && <DialogDescription>{description}</DialogDescription>}
                    </DialogHeader>
                )}

                {children}

                {footer != null && <DialogFooter>{footer}</DialogFooter>}
            </DialogContent>
        </Dialog>
    );
}

export { Modal };
