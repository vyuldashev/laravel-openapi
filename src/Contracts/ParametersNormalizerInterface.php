<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;

interface ParametersNormalizerInterface
{
    /**
     * @return Parameter[]
     */
    public function normalize(): array;
}
