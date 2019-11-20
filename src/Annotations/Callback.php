<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\CallbackFactory;

/**
 * @Annotation
 */
class Callback
{
    /**
     * @Required()
     */
    public $factory;

    public function __construct($values)
    {
        $this->factory = class_exists($values['factory']) ? $values['factory'] : app()->getNamespace().'OpenApi\\Callbacks\\'.$values['factory'];

        if (! is_a($this->factory, CallbackFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of CallbackFactory');
        }
    }
}
