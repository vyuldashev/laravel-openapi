<?php

namespace Examples\Petstore\OpenApi\RequestBodies;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class EmptyRequestBody extends RequestBodyFactory
{
    private RouteInformation $routeInformation;

    public function __construct(RouteInformation $routeInformation)
    {
        $this->routeInformation = $routeInformation;
    }

    public function build(): RequestBody
    {
        return RequestBody::create('EmptyRequestBody')
            ->description('Empty '.$this->routeInformation->method.' body');
    }
}
