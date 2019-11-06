<?php

namespace Vyuldashev\LaravelOpenApi\Http;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Vyuldashev\LaravelOpenApi\Generator;

class OpenApiController
{
    public function show(Generator $generator): OpenApi
    {
        return $generator->generate();
    }
}
