<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Tests\Builders;

use Vyuldashev\LaravelOpenApi\Builders\TagsBuilder;
use Vyuldashev\LaravelOpenApi\Tests\TestCase;

class TagsBuilderTest extends TestCase
{
    /**
     * @dataProvider providerBuild
     *
     * @param array $config
     * @param array $expected
     * @return void
     */
    public function testBuild(array $config, array $expected): void
    {
        $builder = new TagsBuilder();
        $tags = $builder->build($config);
        $this->assertSameAssociativeArray($expected[0], $tags[0]->toArray());
    }

    public static function providerBuild(): array
    {
        return [
            'If the external docs do not exist, it can output the correct json.' => [
                [[
                    'name' => 'post',
                    'description' => 'Posts',
                ]],
                [[
                    'name' => 'post',
                    'description' => 'Posts',
                ]],
            ],
            'If the external docs are present, it can output the correct json.' => [
                [[
                    'name' => 'post',
                    'description' => 'Posts',
                    'externalDocs' => [
                        'description' => 'External API documentation',
                        'url' => 'https://example.com/external-docs',
                    ],
                ]],
                [[
                    'name' => 'post',
                    'description' => 'Posts',
                    'externalDocs' => [
                        'description' => 'External API documentation',
                        'url' => 'https://example.com/external-docs',
                    ],
                ]],
            ],
        ];
    }

    /**
     * Assert equality as an associative array.
     *
     * @param array $expected
     * @param array $actual
     * @return void
     */
    protected function assertSameAssociativeArray(array $expected, array $actual): void
    {
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertSameAssociativeArray($value, $actual[$key]);
                unset($actual[$key]);
                continue;
            }
            self::assertSame($value, $actual[$key]);
            unset($actual[$key]);
        }
        self::assertCount(0, $actual, sprintf('[%s] does not matched keys.', join(', ', array_keys($actual))));
    }
}
