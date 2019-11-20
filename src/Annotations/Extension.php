<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\ExtensionFactory;

/**
 * @Annotation
 */
class Extension
{
    public $factory;

    public $key;

    /** @var string|null */
    public $value;

    public function __construct($values)
    {
        if (isset($values['factory'])) {
            $this->factory = class_exists($values['factory']) ? $values['factory'] : app()->getNamespace().'OpenApi\\Extensions\\'.$values['factory'];

            if (! is_a($this->factory, ExtensionFactory::class, true)) {
                throw new InvalidArgumentException('Factory class must be instance of ExtensionFactory');
            }
        }

        $this->key = $values['key'] ?? null;
        $this->value = $values['value'] ?? null;
    }
}
