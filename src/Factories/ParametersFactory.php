<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;

abstract class ParametersFactory
{
    /**
     * @return Parameter[]
     */
    abstract public function build(): array;
}
