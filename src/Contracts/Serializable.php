<?php

namespace Laravilt\Support\Contracts;

/**
 * Serializable Contract
 *
 * Defines components that can be serialized to different formats.
 */
interface Serializable
{
    /**
     * Serialize component for Laravilt (Blade + Vue.js).
     */
    public function toLaraviltProps(): array;

    /**
     * Serialize component for REST API.
     */
    public function toApiProps(): array;

    /**
     * Serialize component for Flutter.
     */
    public function toFlutterProps(): array;
}
