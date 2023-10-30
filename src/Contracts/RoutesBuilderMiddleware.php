<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use Vyuldashev\LaravelOpenApi\RouteInformation;

interface RoutesBuilderMiddleware
{
    public function after(RouteInformation $routeInformation): RouteInformation;
}
