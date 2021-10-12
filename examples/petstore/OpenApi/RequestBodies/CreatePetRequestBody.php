<?php

namespace Examples\Petstore\OpenApi\RequestBodies;

use Examples\Petstore\OpenApi\Schemas\PetSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Concerns\Referencable;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyFactoryInterface;

class CreatePetRequestBody implements RequestBodyFactoryInterface
{
    use Referencable;

    public function build(): RequestBody
    {
        return RequestBody::create('UserCreate')
            ->description($this->data['custom'])
            ->content(
                MediaType::json()->schema(PetSchema::ref())
            );
    }
}
