<?php


namespace Vyuldashev\LaravelOpenApi\Contracts;


use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

interface ResponseFactoryInterface
{
    public function build(): Response;

    public static function ref(?string $objectId = null): Schema;
}