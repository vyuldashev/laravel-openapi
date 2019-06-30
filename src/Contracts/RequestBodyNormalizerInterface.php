<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;

interface RequestBodyNormalizerInterface
{
    public function normalize(): RequestBody;
}
