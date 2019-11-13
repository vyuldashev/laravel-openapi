<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use Vyuldashev\LaravelOpenApi\Concerns\Referencable;

abstract class ParametersFactory
{
    use Referencable;

    /**
     * @return Parameter[]
     */
    abstract public function build(): array;
}
