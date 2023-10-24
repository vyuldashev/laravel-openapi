<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use Vyuldashev\LaravelOpenApi\RouteInformation;

interface RouteInformationMiddleware
{
    public function after(RouteInformation $routeInformation): RouteInformation;
}
