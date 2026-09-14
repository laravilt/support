import * as LucideIcons from 'lucide-react';
import { icons, type LucideIcon } from 'lucide-react';

/**
 * Every named export of lucide-react. Unlike the `icons` map (canonical names only), the namespace
 * also carries the alias names the Vue packages rely on through `import * as LucideIcons`
 * (`Home`, `CheckCircle`, `CheckCircle2`, `AlertTriangle`, `XCircle`, `BarChart`, …).
 */
const namespace = LucideIcons as unknown as Record<string, unknown>;

function lookup(name: string): LucideIcon | null {
    if (name in icons) {
        return icons[name as keyof typeof icons];
    }

    if (name === 'Icon') {
        return null;
    }

    const candidate = namespace[name];

    // lucide icons are forwardRef objects; skip helpers such as `createLucideIcon` and the `icons` map.
    if (candidate && typeof candidate === 'object' && '$$typeof' in candidate) {
        return candidate as LucideIcon;
    }

    return null;
}

/**
 * Resolve an icon name coming from PHP into a lucide-react component.
 *
 * Accepts `LayoutDashboard`, `layout-dashboard`, `layout_dashboard`, `lucide-layout-dashboard`,
 * `heroicon-o-home` / `heroicon-s-home` (the heroicon prefix is stripped, like the Vue packages do),
 * and lucide alias names (`Home`, `check-circle`, `alert-triangle`, …).
 */
export function resolveIcon(name: string | null | undefined): LucideIcon | null {
    if (!name || typeof name !== 'string') {
        return null;
    }

    const cleaned = name
        .trim()
        .replace(/^heroicon-[osm]-/, '')
        .replace(/^lucide[-:]/i, '');

    const direct = lookup(cleaned);

    if (direct) {
        return direct;
    }

    const pascal = cleaned
        .split(/[-_\s.]+/)
        .filter(Boolean)
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join('');

    const fromPascal = lookup(pascal);

    if (fromPascal) {
        return fromPascal;
    }

    // lucide names digits as their own segment (e.g. "Settings2", "Grid3x3")
    const withoutIconSuffix = pascal.replace(/Icon$/, '');

    return lookup(withoutIconSuffix);
}
