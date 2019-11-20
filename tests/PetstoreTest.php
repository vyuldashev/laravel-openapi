<?php

namespace Vyuldashev\LaravelOpenApi\Tests;

use Examples\Petstore\PetController;
use Illuminate\Support\Facades\Route;
use Vyuldashev\LaravelOpenApi\Builders\Components\SchemasBuilder;

/**
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/examples/v3.0/petstore.yaml
 */
class PetstoreTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('APP_URL=http://petstore.swagger.io/v1');

        parent::setUp();

        SchemasBuilder::in([__DIR__.'/../examples/petstore/OpenApi/Schemas']);

        Route::get('/pets', [PetController::class, 'index']);
    }

    public function testGenerate(): void
    {
        $spec = $this->generate()->toArray();

        $this->assertSame('http://petstore.swagger.io/v1', $spec['servers'][0]['url']);

        $this->assertArrayHasKey('/pets', $spec['paths']);
        $this->assertArrayHasKey('get', $spec['paths']['/pets']);

        $this->assertSame([
            'summary' => 'List all pets.',
            'operationId' => 'listPets',
            'parameters' => [
                [
                    'name' => 'limit',
                    'in' => 'query',
                    'description' => 'How many items to return at one time (max 100)',
                    'required' => false,
                    'schema' => [
                        'format' => 'int32',
                        'type' => 'integer',
                    ],
                ],
            ],
            'responses' => [
                422 => [
                    '$ref' => '#/components/responses/ErrorValidation',
                ],
            ],
        ], $spec['paths']['/pets']['get']);

        $this->assertArrayHasKey('components', $spec);
        $this->assertArrayHasKey('schemas', $spec['components']);
        $this->assertArrayHasKey('Pet', $spec['components']['schemas']);

        $this->assertSame([
            'type' => 'object',
            'required' => [
                'id',
                'name',
            ],
            'properties' => [
                'id' => [
                    'format' => 'int64',
                    'type' => 'integer',
                ],
                'name' => [
                    'type' => 'string',
                ],
                'tag' => [
                    'type' => 'string',
                ],
            ],
        ], $spec['components']['schemas']['Pet']);
    }
}
