<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Tests\Builders;

use Vyuldashev\LaravelOpenApi\Builders\ServersBuilder;
use Vyuldashev\LaravelOpenApi\Tests\TestCase;

class ServersBuilderTest extends TestCase
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
        $SUT = new ServersBuilder();
        $servers = $SUT->build($config);
        $this->assertSameAssociativeArray($expected[0], $servers[0]->toArray());
    }

    public static function providerBuild(): array
    {
        return [
            'If the variables field does not exist, it is possible to output the correct json.' => [
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                    'variables' => [],
                ]],
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                ]],
            ],
            'If the variables field is present, it can output the correct json.' => [
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_defalut',
                            'description' => 'variable_description',
                        ],
                    ],
                ]],
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_defalut',
                            'description' => 'variable_description',
                        ],
                    ],
                ]],
            ],
            'If there is a variables field containing enum, it can output the correct json.' => [
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_defalut',
                            'description' => 'variable_description',
                            'enum' => [
                                'A',
                                'B',
                                'C',
                            ],
                        ],
                    ],
                ]],
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_defalut',
                            'description' => 'variable_description',
                            'enum' => [
                                'A',
                                'B',
                                'C',
                            ],
                        ],
                    ],
                ]],
            ],
            'If there are variables fields in multiple formats, it is possible to output the correct json.' => [
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_defalut',
                            'description' => 'variable_description',
                            'enum' => ['A', 'B'],
                        ],
                        'variable_name_B' => [
                            'default' => 'sample',
                            'description' => 'sample',
                        ],
                    ],
                ]],
                [[
                    'url' => 'http://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_defalut',
                            'description' => 'variable_description',
                            'enum' => ['A', 'B'],
                        ],
                        'variable_name_B' => [
                            'default' => 'sample',
                            'description' => 'sample',
                        ],
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
