<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Response
{
    public string $factory;

    public ?int $statusCode;

    public ?string $description;

    public function __construct(string $factory, int $statusCode = null, string $description = null)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace().'OpenApi\\Responses\\'.$factory;

        if (! is_a($this->factory, ResponseFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ResponseFactory');
        }

        $this->statusCode = $statusCode;
        $this->description = $description;
    }
}
