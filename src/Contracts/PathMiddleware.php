<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Vyuldashev\LaravelOpenApi\RouteInformation;

interface PathMiddleware
{
    public function before(RouteInformation $routeInformation): void;

    public function after(PathItem $pathItem): PathItem;
}
