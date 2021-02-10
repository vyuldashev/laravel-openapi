<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Collection
{
    /** @var string|array<string> */
    public array|string $name;

    public function __construct(string|array $name = 'default')
    {
        $this->name = $name;
    }
}
