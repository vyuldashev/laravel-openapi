<?php

namespace Vyuldashev\LaravelOpenApi\Tests\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use phpDocumentor\Reflection\DocBlock;
use Vyuldashev\LaravelOpenApi\Attributes\Operation as AttributesOperation;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\SecurityBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\OperationsBuilder;
use Vyuldashev\LaravelOpenApi\Factories\SecuritySchemeFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;
use Vyuldashev\LaravelOpenApi\Tests\TestCase;

class SecurityBuilderTest extends TestCase
{
    /**
     * We're just making sure we're getting the expected output.
     */
    public function testWeCanBuildUpTheSecurityScheme(): void
    {
        $securityFactory = resolve(JwtSecurityScheme::class);
        $testJwtScheme = $securityFactory->build();

        $globalRequirement = SecurityRequirement::create('JWT')
            ->securityScheme($testJwtScheme);

        $components = Components::create()
            ->securitySchemes($testJwtScheme);

        $operation = Operation::create()
            ->action('get');

        $openApi = OpenApi::create()
            ->security($globalRequirement)
            ->components($components)
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations($operation)
            );

        self::assertSame([
            'paths' => [
                '/foo' => [
                    'get' => [],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'JWT' => [
                        'type' => 'http',
                        'name' => 'TestScheme',
                        'in' => 'header',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ],
                ],
            ],
            'security' => [
                [
                    'JWT' => [],
                ],
            ],
        ], $openApi->toArray());
    }

    /**
     * We're just verifying that the builder is capable of
     * adding security information to the operation.
     */
    public function testWeCanAddOperationSecurityUsingBuilder()
    {
        $securityFactory = resolve(JwtSecurityScheme::class);
        $testJwtScheme = $securityFactory->build();

        $globalRequirement = SecurityRequirement::create('JWT')
            ->securityScheme($testJwtScheme);

        $components = Components::create()
            ->securitySchemes($testJwtScheme);

        $routeInfo = new RouteInformation;
        $routeInfo->action = 'get';
        $routeInfo->name = 'test route';
        $routeInfo->actionAttributes = collect([
            new AttributesOperation(security: JwtSecurityScheme::class),
        ]);
        $routeInfo->uri = '/example';

        /** @var SecurityBuilder */
        $builder = resolve(SecurityBuilder::class);

        $operation = Operation::create()
            ->security(...$builder->build($routeInfo))
            ->action('get');

        $openApi = OpenApi::create()
            ->security($globalRequirement)
            ->components($components)
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations($operation)
            );

        self::assertSame([
            'paths' => [
                '/foo' => [
                    'get' => [
                        'security' => [
                            [
                                'JWT' => [],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'JWT' => [
                        'type' => 'http',
                        'name' => 'TestScheme',
                        'in' => 'header',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ],
                ],
            ],
            'security' => [
                [
                    'JWT' => [],
                ],
            ],
        ], $openApi->toArray());
    }

    /**
     * He's the main part of the PR. It's not possible to turn
     * off security for an operation.
     */
    public function testWeCanAddTurnOffOperationSecurityUsingBuilder()
    {
        $securityFactory = resolve(JwtSecurityScheme::class);
        $testJwtScheme = $securityFactory->build();

        $globalRequirement = SecurityRequirement::create('JWT')
            ->securityScheme($testJwtScheme);

        $components = Components::create()
            ->securitySchemes($testJwtScheme);

        $routeInfo = new RouteInformation;
        $routeInfo->parameters = collect();
        $routeInfo->action = 'foo';
        $routeInfo->method = 'get';
        $routeInfo->name = 'test route';
        $routeInfo->actionDocBlock = new DocBlock('Test');
        $routeInfo->actionAttributes = collect([
            /**
             * we can set secuity to null to turn it off, as
             * that's the default value. So '' is next best
             * option?
             */
            new AttributesOperation(security: ''),
        ]);

        /** @var OperationsBuilder */
        $operationsBuilder = resolve(OperationsBuilder::class);

        $operations = $operationsBuilder->build([$routeInfo]);

        $openApi = OpenApi::create()
            ->security($globalRequirement)
            ->components($components)
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations(...$operations)
            );

        self::assertSame([
            'paths' => [
                '/foo' => [
                    'get' => [
                        'summary' => 'Test',
                        'security' => [],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'JWT' => [
                        'type' => 'http',
                        'name' => 'TestScheme',
                        'in' => 'header',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ],
                ],
            ],
            'security' => [
                [
                    'JWT' => [],
                ],
            ],
        ], $openApi->toArray());
    }
}

class JwtSecurityScheme extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return SecurityScheme::create('JWT')
            ->name('TestScheme')
            ->type(SecurityScheme::TYPE_HTTP)
            ->in(SecurityScheme::IN_HEADER)
            ->scheme('bearer')
            ->bearerFormat('JWT');
    }
}
