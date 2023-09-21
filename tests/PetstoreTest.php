<?php

namespace Vyuldashev\LaravelOpenApi\Tests;

use Examples\Petstore\PetController;
use Illuminate\Support\Facades\Route;

/**
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/examples/v3.0/petstore.yaml
 */
class PetstoreTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('APP_URL=http://petstore.swagger.io/v1');

        parent::setUp();

        Route::get('/pets', [PetController::class, 'index']);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('openapi.locations.schemas', [
            __DIR__.'/../examples/petstore/OpenApi/Schemas',
        ]);
    }

    public function testGenerate(): void
    {
        $spec = $this->generate()->toArray();

        self::assertSame('http://petstore.swagger.io/v1', $spec['servers'][0]['url']);

        self::assertArrayHasKey('/pets', $spec['paths']);
        self::assertArrayHasKey('get', $spec['paths']['/pets']);

        self::assertSame([
            'summary' => 'List all pets.',
            'description' => 'List all pets from the database.',
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
            'deprecated' => true,
        ], $spec['paths']['/pets']['get']);

        self::assertArrayHasKey('components', $spec);
        self::assertArrayHasKey('schemas', $spec['components']);
        self::assertArrayHasKey('Pet', $spec['components']['schemas']);

        self::assertSame([
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
