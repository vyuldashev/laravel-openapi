<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

interface ParametersFactoryInterface
{
    /**
     * @return Parameter[]
     */
    public function build(): array;

    public static function ref(?string $objectId = null): Schema;
}
