<?php

namespace Laravilt\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Laravilt\Support\Concerns\EvaluatesClosures;
use Laravilt\Support\Concerns\HasColumnSpan;
use Laravilt\Support\Concerns\HasHelperText;
use Laravilt\Support\Concerns\HasLabel;
use Laravilt\Support\Concerns\HasVisibility;
use Laravilt\Support\Concerns\InteractsWithState;
use Laravilt\Support\Contracts\Buildable;
use Laravilt\Support\Contracts\Serializable;

/**
 * Base Component Class
 *
 * Foundation for all Laravilt UI components. Provides:
 * - Factory pattern via make()
 * - Multi-platform serialization (Laravilt, API, Flutter)
 * - RTL/LTR support
 * - Theme support
 * - Visibility control
 * - State management
 */
abstract class Component implements Arrayable, Buildable, Jsonable, Serializable
{
    use EvaluatesClosures;
    use HasColumnSpan;
    use HasHelperText;
    use HasLabel;
    use HasVisibility;
    use InteractsWithState;

    /**
     * The component's unique name/key.
     */
    protected string $name;

    /**
     * The Blade view for this component.
     */
    protected string $view;

    /**
     * Additional metadata for this component.
     */
    protected array $meta = [];

    /**
     * Create a new component instance using the factory pattern.
     */
    public static function make(string $name): static
    {
        $static = app(static::class);
        $static->name = $name;
        $static->setUp();

        return $static;
    }

    /**
     * Set up the component (override in subclasses for initialization).
     */
    protected function setUp(): void
    {
        //
    }

    /**
     * Get the component's name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get a unique ID for this component instance.
     */
    public function getId(): string
    {
        return 'laravilt-'.$this->name.'-'.md5($this->name.spl_object_id($this));
    }

    /**
     * Set metadata for this component.
     */
    public function meta(array $meta): static
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * Get component metadata.
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Serialize component for Laravilt (Blade + Vue.js).
     */
    public function toLaraviltProps(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'state' => $this->getState(),
            'label' => $this->getLabel(),
            'helperText' => $this->getHelperText(),
            'hidden' => $this->isHidden(),
            'columnSpan' => $this->getColumnSpan(),
            'rtl' => $this->isRTL(),
            'theme' => $this->getTheme(),
            'locale' => app()->getLocale(),
            'meta' => $this->getMeta(),
        ];
    }

    /**
     * Serialize component for REST API.
     */
    public function toApiProps(): array
    {
        return [
            'type' => class_basename(static::class),
            'name' => $this->getName(),
            'value' => $this->getState(),
            'label' => $this->getLabel(),
            'helperText' => $this->getHelperText(),
            'meta' => $this->getMeta(),
        ];
    }

    /**
     * Serialize component for Flutter.
     */
    public function toFlutterProps(): array
    {
        return [
            'widget' => class_basename(static::class),
            'name' => $this->getName(),
            'value' => $this->getState(),
            'label' => $this->getLabel(),
            'helperText' => $this->getHelperText(),
            'isRTL' => $this->isRTL(),
            'meta' => $this->getMeta(),
        ];
    }

    /**
     * Render the component to HTML.
     */
    public function render(): string
    {
        if ($this->isHidden()) {
            return '';
        }

        return view($this->view, [
            'component' => $this,
        ])->render();
    }

    /** Check if current locale is RTL. */
    protected function isRTL(): bool
    {
        return \Laravilt\Support\Utilities\Translator::isRTL();
    }

    /** Get current theme. */
    protected function getTheme(): string
    {
        return session('theme', 'light');
    }

    /** Convert component to array (for JSON serialization). */
    public function toArray(): array
    {
        return $this->toLaraviltProps();
    }

    /** Convert component to JSON. */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
