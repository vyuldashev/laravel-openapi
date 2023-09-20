<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\SecuritySchemeFactory;

#[Attribute(Attribute::TARGET_METHOD)]
class Operation
{
    public ?string $id;

    /** @var array<string> */
    public array $tags;

    public ?string $security;

    public ?string $method;

    public ?array $servers;

    public ?string $summary;

    public ?string $description;

    public ?bool $deprecated;

    /**
     * @param  string|null  $id
     * @param  array  $tags
     * @param  \Vyuldashev\LaravelOpenApi\Factories\SecuritySchemeFactory|string|null  $security
     * @param  string|null  $method
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $id = null, array $tags = [], string $security = null, string $method = null, array $servers = null, string $summary = null, string $description = null, bool $deprecated = null)
    {
        $this->id = $id;
        $this->tags = $tags;
        $this->method = $method;
        $this->servers = $servers;
        $this->summary = $summary;
        $this->description = $description;
        $this->deprecated = $deprecated;

        if ($security === '') {
            //user wants to turn off security on this operation
            $this->security = $security;

            return;
        }

        if ($security) {
            $this->security = class_exists($security) ? $security : app()->getNamespace().'OpenApi\\SecuritySchemes\\'.$security;

            if (! is_a($this->security, SecuritySchemeFactory::class, true)) {
                throw new InvalidArgumentException(
                    sprintf('Security class is either not declared or is not an instance of %s', SecuritySchemeFactory::class)
                );
            }
        }
    }
}
