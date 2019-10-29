<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use ReflectionType;

class SchemaHelpers
{
    public static function guessFromReflectionType(ReflectionType $reflectionType): ?Schema
    {
        switch ($reflectionType->getName()) {
            case 'int':
                return Schema::integer();
            case 'string':
                return Schema::string();
            case 'bool':
                return Schema::boolean();
        }

        return null;
    }
}
