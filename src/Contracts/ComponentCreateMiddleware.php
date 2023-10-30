<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;

interface ComponentCreateMiddleware
{
    public function before(Components $components): Components;
}
