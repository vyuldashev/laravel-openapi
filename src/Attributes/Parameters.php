<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersFactoryInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class Parameters
{
    public string $factory;

    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Parameters\\' . $factory;

        if (!is_a($this->factory, ParametersFactoryInterface::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ParametersFactoryInterface');
        }
    }
}
