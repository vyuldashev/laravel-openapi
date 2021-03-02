<?php

namespace Examples\Petstore;

use Examples\Petstore\OpenApi\Parameters\ListPetsParameters;
use Examples\Petstore\OpenApi\RequestBodies\CreatePetRequestBody;
use Examples\Petstore\OpenApi\Responses\ErrorValidationResponse;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class PetController
{
    /**
     * List all pets.
     */
    #[OpenApi\Operation('listPets')]
    #[OpenApi\Parameters(ListPetsParameters::class, "Parameters custom data")]
    #[OpenApi\Response(ErrorValidationResponse::class, 422, "", "Response custom data")]
    public function index()
    {
    }

    /**
     * Create pet.
     */
    #[OpenApi\Operation('createPet')]
    #[OpenApi\RequestBody(CreatePetRequestBody::class, ["custom" => "My custom data"])]
    public function create()
    {
    }
}
