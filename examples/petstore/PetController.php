<?php

namespace Examples\Petstore;

use Examples\Petstore\OpenApi\Parameters\ListPetsParameters;
use Examples\Petstore\OpenApi\Responses\ErrorValidationResponse;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class PetController
{
    /**
     * List all pets.
     */
    #[OpenApi\Operation(id: 'listPets', summary: 'List all pets.', description: 'List all pets from the database.', deprecated: true)]
    #[OpenApi\Parameters(ListPetsParameters::class)]
    #[OpenApi\Response(ErrorValidationResponse::class, 422)]
    public function index()
    {
    }
}
