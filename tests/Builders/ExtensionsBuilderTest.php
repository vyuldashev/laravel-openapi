<?php

namespace Vyuldashev\LaravelOpenApi\Tests\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Vyuldashev\LaravelOpenApi\Annotations\Extension;
use Vyuldashev\LaravelOpenApi\Builders\ExtensionsBuilder;
use Vyuldashev\LaravelOpenApi\Factories\ExtensionFactory;
use Vyuldashev\LaravelOpenApi\Tests\TestCase;

class ExtensionsBuilderTest extends TestCase
{
    public function testBuildUsingFactory(): void
    {
        $operation = Operation::create()->action('get');

        $openApi = OpenApi::create()
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations($operation)
            );

        /** @var ExtensionsBuilder $builder */
        $builder = resolve(ExtensionsBuilder::class);
        $builder->build($operation, collect([
            new Extension(['factory' => FakeExtension::class]),
        ]));

        $this->assertSame([
            'paths' => [
                '/foo' => [
                    'get' => [
                        'x-uuid' => ['format' => 'uuid', 'type' => 'string'],
                    ],
                ],
            ],
        ], $openApi->toArray());
    }

    public function testBuildUsingKeyValue(): void
    {
        $operation = Operation::create()->action('get');

        $openApi = OpenApi::create()
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations($operation)
            );

        /** @var ExtensionsBuilder $builder */
        $builder = resolve(ExtensionsBuilder::class);
        $builder->build($operation, collect([
            new Extension(['key' => 'foo', 'value' => 'bar']),
            new Extension(['key' => 'x-key', 'value' => '1']),
        ]));

        $this->assertSame([
            'paths' => [
                '/foo' => [
                    'get' => [
                        'x-foo' => 'bar',
                        'x-key' => '1',
                    ],
                ],
            ],
        ], $openApi->toArray());
    }
}

class FakeExtension extends ExtensionFactory
{
    public function key(): string
    {
        return 'uuid';
    }

    /**
     * @return string|null|array
     */
    public function value()
    {
        return Schema::string()->format(Schema::FORMAT_UUID);
    }
}
