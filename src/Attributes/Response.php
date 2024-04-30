<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Response
{
    public ?array $factories = null;

    public ?string $factory = null;

    public ?int $statusCode = null;

    public ?string $description = null;

    public function __construct(?string $factory = null, ?int $statusCode = null, ?string $description = null)
    {
        if ($factory === null && $this->factories !== null) {
            return;
        }
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace().'OpenApi\\Responses\\'.$factory;

        if (! is_a($this->factory, ResponseFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ResponseFactory');
        }

        $this->statusCode = $statusCode;
        $this->description = $description;
    }
}
