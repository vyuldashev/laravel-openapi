<?php

namespace Examples\Petstore;

use Vyuldashev\LaravelOpenApi\Annotations as OpenApi;

/**
 * @OpenApi\PathItem()
 */
class PetController
{
    /**
     * List all pets.
     *
     * @OpenApi\Operation(id="listPets")
     * @OpenApi\Parameters(factory="Examples\Petstore\OpenApi\Parameters\ListPetsParameters")
     * @OpenApi\Response(factory="Examples\Petstore\OpenApi\Responses\ErrorValidationResponse", statusCode=422)
     */
    public function index()
    {
    }
}
