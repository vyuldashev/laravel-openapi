<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use ReflectionType;

class SchemaFactory
{
    public static function createFromReflectionType(ReflectionType $reflectionType): ?Schema
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
