<?php

namespace Vyuldashev\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

/**
 * Interface RequestBodyFactoryInterface.
 * @var $data
 */
interface RequestBodyFactoryInterface
{
    public function build(): RequestBody;

    public static function ref(?string $objectId = null): Schema;
}
