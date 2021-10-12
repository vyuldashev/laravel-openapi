<?php

namespace Vyuldashev\LaravelOpenApi\Concerns;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\CallbackFactoryInterface;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersFactoryInterface;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyFactoryInterface;
use Vyuldashev\LaravelOpenApi\Contracts\ResponseFactoryInterface;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Contracts\SchemaFactoryInterface;
use Vyuldashev\LaravelOpenApi\Contracts\SecuritySchemeFactoryInterface;

trait Referencable
{
    public static function ref(?string $objectId = null): Schema
    {
        $instance = app(static::class);

        if (! $instance instanceof Reusable) {
            throw new InvalidArgumentException('"'.static::class.'" must implement "'.Reusable::class.'" in order to be referencable.');
        }

        $baseRef = null;

        if ($instance instanceof CallbackFactoryInterface) {
            $baseRef = '#/components/callbacks/';
        } elseif ($instance instanceof ParametersFactoryInterface) {
            $baseRef = '#/components/parameters/';
        } elseif ($instance instanceof RequestBodyFactoryInterface) {
            $baseRef = '#/components/requestBodies/';
        } elseif ($instance instanceof ResponseFactoryInterface) {
            $baseRef = '#/components/responses/';
        } elseif ($instance instanceof SchemaFactoryInterface) {
            $baseRef = '#/components/schemas/';
        } elseif ($instance instanceof SecuritySchemeFactoryInterface) {
            $baseRef = '#/components/securitySchemes/';
        }

        return Schema::ref($baseRef.$instance->build()->objectId, $objectId);
    }
}
