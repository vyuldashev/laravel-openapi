<?php


namespace Vyuldashev\LaravelOpenApi\Contracts;


use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

interface SecuritySchemeFactoryInterface
{
    public function build(): SecurityScheme;
}