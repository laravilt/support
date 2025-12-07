<?php

namespace Laravilt\Support\Enums;

enum ImageSize: string
{
    case ExtraSmall = 'xs';
    case Small = 'sm';
    case Medium = 'md';
    case Large = 'lg';
    case ExtraLarge = 'xl';
    case TwoExtraLarge = '2xl';

    /**
     * Get the pixel size for the image.
     */
    public function getSize(): int
    {
        return match ($this) {
            self::ExtraSmall => 24,
            self::Small => 32,
            self::Medium => 40,
            self::Large => 48,
            self::ExtraLarge => 56,
            self::TwoExtraLarge => 64,
        };
    }

    /**
     * Get the size as a CSS value with px unit.
     */
    public function getCssSize(): string
    {
        return $this->getSize() . 'px';
    }
}
