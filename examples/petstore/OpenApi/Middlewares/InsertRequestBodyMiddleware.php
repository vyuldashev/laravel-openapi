<?php

namespace Examples\Petstore\OpenApi\Middlewares;

use Examples\Petstore\OpenApi\RequestBodies\EmptyRequestBody;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;
use Vyuldashev\LaravelOpenApi\Contracts\RoutesBuilderMiddleware;
use Vyuldashev\LaravelOpenApi\Generator;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class InsertRequestBodyMiddleware implements RoutesBuilderMiddleware
{
    public function after(RouteInformation $routeInformation): RouteInformation
    {
        $routeInformation->actionAttributes[] = new OpenApi\RequestBody(EmptyRequestBody::class);
        $routeInformation->actionAttributes[] = new OpenApi\Collection([Generator::COLLECTION_DEFAULT]);

        return $routeInformation;
    }
}
