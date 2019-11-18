<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

abstract class CallbackFactory
{
    abstract public function build(): PathItem;
}
