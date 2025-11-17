<?php

namespace Laravilt\Support\LaraviltCore\Bridge;

use Laravilt\Support\Component;

/**
 * Component Serializer
 *
 * Handles serialization of components for different platforms.
 */
class ComponentSerializer
{
    /**
     * Serialize a component for Laravilt (Blade + Vue).
     */
    public static function toLaravilt(Component $component): array
    {
        return $component->toLaraviltProps();
    }

    /**
     * Serialize a component for REST API.
     */
    public static function toApi(Component $component): array
    {
        return $component->toApiProps();
    }

    /**
     * Serialize a component for Flutter.
     */
    public static function toFlutter(Component $component): array
    {
        return $component->toFlutterProps();
    }

    /**
     * Serialize multiple components.
     */
    public static function serializeMany(array $components, string $format = 'laravilt'): array
    {
        return array_map(function ($component) use ($format) {
            return match ($format) {
                'api' => static::toApi($component),
                'flutter' => static::toFlutter($component),
                default => static::toLaravilt($component),
            };
        }, $components);
    }

    /**
     * Serialize a component to JSON.
     */
    public static function toJson(Component $component, string $format = 'laravilt'): string
    {
        $data = match ($format) {
            'api' => static::toApi($component),
            'flutter' => static::toFlutter($component),
            default => static::toLaravilt($component),
        };

        return json_encode($data);
    }
}
