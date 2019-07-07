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
     */
    public function index()
    {

    }
}
