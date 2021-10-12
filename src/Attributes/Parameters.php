<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersFactoryInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class Parameters
{
    public string $factory;
    public $data;

    public function __construct(string $factory, $data = null)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace().'OpenApi\\Parameters\\'.$factory;
        $this->data = $data;

        if (! is_a($this->factory, ParametersFactoryInterface::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ParametersFactoryInterface');
        }
    }
}
