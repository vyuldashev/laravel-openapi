<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

interface ExtensionFactoryInterface
{
    public function key(): string;

    /**
     * @return string|null|array
     */
    public function value();
}
