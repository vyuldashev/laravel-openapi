<?php

namespace Examples\Petstore\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class PetSchema extends SchemaFactory implements Reusable
{
    /**
     * @return Schema
     * @throws InvalidArgumentException
     */
    public function build(): Schema
    {
        return Schema::object('Pet')
            ->required('id', 'name')
            ->properties(
                Schema::integer('id')->format(Schema::FORMAT_INT64),
                Schema::string('name'),
                Schema::string('tag')
            );
    }
}
