<?php


namespace Vyuldashev\LaravelOpenApi\Contracts;


use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

interface SchemaFactoryInterface
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     */
    public function build(): SchemaContract;

    public static function ref(?string $objectId = null): Schema;
}