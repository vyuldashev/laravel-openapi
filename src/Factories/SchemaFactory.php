<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

abstract class SchemaFactory
{
    abstract public function build(): Schema;
}
