<?php

namespace Laravilt\Support\LaraviltCore\Blade\Components;

use Illuminate\View\Component;

/**
 * Laravilt Form Component
 *
 * Blade component for SPA-enabled forms with automatic serialization.
 */
class Form extends Component
{
    /** Form action URL */
    public string $action;

    /** HTTP method (GET, POST, PUT, PATCH, DELETE) */
    public string $method;

    /** Form CSS classes */
    public string $class;

    /** Enable SPA submission */
    public bool $spa;

    /** Component props data */
    public ?array $data;

    /** Form encoding type */
    public string $enctype;

    /** Form ID */
    public ?string $formId;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $action = '',
        string $method = 'POST',
        string $class = '',
        bool $spa = true,
        ?array $data = null,
        string $enctype = 'application/x-www-form-urlencoded',
        ?string $formId = null
    ) {
        $this->action = $action;
        $this->method = strtoupper($method);
        $this->class = $class;
        $this->spa = $spa;
        $this->data = $data;
        $this->enctype = $enctype;
        $this->formId = $formId ?? 'laravilt-form-' . uniqid();
    }

    /**
     * Get the actual HTTP method for the form.
     */
    public function getFormMethod(): string
    {
        return in_array($this->method, ['GET', 'POST']) ? $this->method : 'POST';
    }

    /**
     * Check if method spoofing is needed.
     */
    public function needsMethodSpoofing(): bool
    {
        return ! in_array($this->method, ['GET', 'POST']);
    }

    /**
     * Get the spoofed method.
     */
    public function getSpoofedMethod(): string
    {
        return $this->method;
    }

    /**
     * Get form attributes as array.
     */
    public function getFormAttributes(): array
    {
        $attributes = [
            'id' => $this->formId,
            'action' => $this->action,
            'method' => $this->getFormMethod(),
            'enctype' => $this->enctype,
        ];

        if ($this->class) {
            $attributes['class'] = $this->class;
        }

        if ($this->spa) {
            $attributes['data-laravilt-form'] = 'true';
        }

        if ($this->data) {
            $attributes['data-laravilt-props'] = json_encode($this->data);
        }

        return $attributes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('laravilt::blade.form');
    }
}
