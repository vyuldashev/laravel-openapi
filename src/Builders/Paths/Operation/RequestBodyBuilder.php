<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody as RequestBodyAttribute;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyFactoryInterface;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class RequestBodyBuilder
{
    public function build(RouteInformation $route): ?RequestBody
    {
        /** @var RequestBodyAttribute|null $requestBody */
        $requestBody = $route->actionAttributes->first(static fn(object $attribute) => $attribute instanceof RequestBodyAttribute);

        if ($requestBody) {
            /** @var RequestBodyFactoryInterface $requestBodyFactory */
            $requestBodyFactory = app($requestBody->factory);
            // little bit magic, add custom data into factory
            $requestBodyFactory->data = $requestBody->data;
            $requestBody = $requestBodyFactory->build();

            if ($requestBodyFactory instanceof Reusable) {
                return RequestBody::ref('#/components/requestBodies/' . $requestBody->objectId);
            }
        }

        return $requestBody;
    }
}
