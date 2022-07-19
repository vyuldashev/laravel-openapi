<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;

abstract class ServerFactory
{
    abstract public function build(): Server;
}
