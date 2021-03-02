<?php


namespace Vyuldashev\LaravelOpenApi\Contracts;


use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

interface CallbackFactoryInterface
{
    public function build(): PathItem;
}