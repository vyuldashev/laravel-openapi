<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\ResponseFactoryInterface;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Response
{
    public string $factory;

    public ?int $statusCode;

    public ?string $description;

    public $data;

    public function __construct(string $factory, int $statusCode = null, string $description = null, $data = null)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Responses\\' . $factory;
        $this->data = $data;

        if (!is_a($this->factory, ResponseFactoryInterface::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ResponseFactoryInterface');
        }

        $this->statusCode = $statusCode;
        $this->description = $description;
    }
}
