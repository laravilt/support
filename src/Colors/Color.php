<?php

namespace Laravilt\Support\Colors;

class Color
{
    // Primary colors
    public const Slate = '#64748b';

    public const Gray = '#6b7280';

    public const Zinc = '#71717a';

    public const Neutral = '#737373';

    public const Stone = '#78716c';

    public const Red = '#ef4444';

    public const Orange = '#f97316';

    public const Amber = '#f59e0b';

    public const Yellow = '#eab308';

    public const Lime = '#84cc16';

    public const Green = '#10b981';

    public const Emerald = '#10b981';

    public const Teal = '#14b8a6';

    public const Cyan = '#06b6d4';

    public const Sky = '#0ea5e9';

    public const Blue = '#3b82f6';

    public const Indigo = '#6366f1';

    public const Violet = '#8b5cf6';

    public const Purple = '#a855f7';

    public const Fuchsia = '#d946ef';

    public const Pink = '#ec4899';

    public const Rose = '#f43f5e';

    /**
     * Get all available colors.
     */
    public static function all(): array
    {
        return [
            'slate' => self::Slate,
            'gray' => self::Gray,
            'zinc' => self::Zinc,
            'neutral' => self::Neutral,
            'stone' => self::Stone,
            'red' => self::Red,
            'orange' => self::Orange,
            'amber' => self::Amber,
            'yellow' => self::Yellow,
            'lime' => self::Lime,
            'green' => self::Green,
            'emerald' => self::Emerald,
            'teal' => self::Teal,
            'cyan' => self::Cyan,
            'sky' => self::Sky,
            'blue' => self::Blue,
            'indigo' => self::Indigo,
            'violet' => self::Violet,
            'purple' => self::Purple,
            'fuchsia' => self::Fuchsia,
            'pink' => self::Pink,
            'rose' => self::Rose,
        ];
    }
}
