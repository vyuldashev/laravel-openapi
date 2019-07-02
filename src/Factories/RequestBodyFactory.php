<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;

abstract class RequestBodyFactory
{
    abstract public function build(): RequestBody;
}
