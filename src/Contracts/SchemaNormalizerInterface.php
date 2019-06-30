<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

interface SchemaNormalizerInterface
{
    public function normalize(): Schema;
}
