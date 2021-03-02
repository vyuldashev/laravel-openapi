<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use Vyuldashev\LaravelOpenApi\Contracts\SecuritySchemeFactoryInterface;

abstract class SecuritySchemeFactory implements SecuritySchemeFactoryInterface
{
    abstract public function build(): SecurityScheme;
}
