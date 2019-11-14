<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\ExtensionFactory;

/**
 * @Annotation
 */
class Extension
{
    /**
     * @Required()
     */
    public $factory;

    public function __construct($values)
    {
        $this->factory = class_exists($values['factory']) ? $values['factory'] : app()->getNamespace().'OpenApi\\Extensions\\'.$values['factory'];

        if (!is_a($this->factory, ExtensionFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ExtensionFactory');
        }
    }
}
