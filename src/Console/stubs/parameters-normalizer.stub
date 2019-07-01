<?php

namespace DummyNamespace;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersNormalizerInterface;

class DummyClass implements ParametersNormalizerInterface
{
    /**
     * @return Parameter[]
     */
    public function normalize(): array
    {
        return [

            Parameter::query()
                ->name('parameter-name')
                ->description('Parameter description')
                ->required(false)
                ->schema(Schema::string()),

        ];
    }
}
