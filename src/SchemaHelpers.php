<?php

namespace Vyuldashev\LaravelOpenApi;

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
