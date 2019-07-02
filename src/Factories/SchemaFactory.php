<?php

namespace Vyuldashev\LaravelOpenApi\Factory;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

abstract class SchemaFactory
{
    abstract public function build(): Schema;
}
