<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\CallbackFactoryInterface;

#[Attribute]
class Callback
{
    public string $factory;

    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Callbacks\\' . $factory;

        if (!is_a($this->factory, CallbackFactoryInterface::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of CallbackFactoryInterface');
        }
    }
}
