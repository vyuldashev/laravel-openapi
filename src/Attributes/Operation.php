<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Operation
{
    public ?string $id;

    /** @var array<string> */
    public array $tags;

    public ?string $method;

    public function __construct(string $id = null, array $tags = [], string $method = null)
    {
        $this->id = $id;
        $this->tags = $tags;
        $this->method = $method;
    }
}
