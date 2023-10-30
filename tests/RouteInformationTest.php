<?php

namespace Vyuldashev\LaravelOpenApi\Tests;

use Examples\Petstore\OpenApi\Middlewares\InsertRequestBodyMiddleware;
use Examples\Petstore\PetController;
use Illuminate\Support\Facades\Route;

class RouteInformationTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('APP_URL=http://petstore.swagger.io/v1');

        parent::setUp();

        Route::get('/pets', [PetController::class, 'index']);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set(
            'openapi.locations.schemas',
            [__DIR__.'/../examples/petstore/OpenApi/Schemas']
        );

        $app['config']->set(
            'openapi.collections.default.middlewares.paths',
            [InsertRequestBodyMiddleware::class]
        );
    }

    public function testGenerateWithAdditionalAttributes(): void
    {
        $spec = $this->generate()->toArray();

        self::assertArrayHasKey('/pets', $spec['paths']);
        self::assertArrayHasKey('get', $spec['paths']['/pets']);
        self::assertArrayHasKey('requestBody', $spec['paths']['/pets']['get']);
        self::assertArrayHasKey('description', $spec['paths']['/pets']['get']['requestBody']);

        self::assertEquals('Empty get body', $spec['paths']['/pets']['get']['requestBody']['description']);
    }
}
