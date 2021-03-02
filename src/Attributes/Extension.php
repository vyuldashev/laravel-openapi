<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\ExtensionFactoryInterface;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Extension
{
    public ?string $factory;
    public ?string $key;
    public ?string $value;

    public function __construct(string $factory = null, string $key = null, string $value = null)
    {
        if ($factory) {
            $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Extensions\\' . $factory;

            if (!is_a($this->factory, ExtensionFactoryInterface::class, true)) {
                throw new InvalidArgumentException('Factory class must be instance of ExtensionFactoryInterface');
            }
        }

        $this->factory ??= null;
        $this->key = $key;
        $this->value = $value;
    }
}
