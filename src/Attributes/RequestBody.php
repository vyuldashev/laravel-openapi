<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyFactoryInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class RequestBody
{
    public string $factory;

    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\RequestBodies\\' . $factory;

        if (!is_a($this->factory, RequestBodyFactoryInterface::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of RequestBodyFactoryInterface');
        }
    }
}
