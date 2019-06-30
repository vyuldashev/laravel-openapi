<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

interface ResponseNormalizerInterface
{
    public function normalize(): Response;
}
