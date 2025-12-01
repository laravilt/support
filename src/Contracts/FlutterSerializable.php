<?php

namespace Laravilt\Support\Contracts;

interface FlutterSerializable
{
    /**
     * Serialize the component to Flutter props.
     *
     * @return array<string, mixed>
     */
    public function toFlutterProps(): array;
}
