<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Operation
{
    public ?string $id;

    /** @var array<string> */
    public array $tags;

    /** @var array<string> */
    public array $security;

    public ?string $method;

    public function __construct(string $id = null, array $tags = [], array $security = [], string $method = null)
    {
        $this->id = $id;
        $this->tags = $tags;
        $this->security = $security;
        $this->method = $method;
    }
}
