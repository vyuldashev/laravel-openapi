<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Tests\Builders;

use Vyuldashev\LaravelOpenApi\Builders\InfoBuilder;
use Vyuldashev\LaravelOpenApi\Tests\TestCase;

class InfoBuilderTest extends TestCase
{
    /**
     * @dataProvider providerBuildContact
     *
     * @param  array  $config
     * @param  array  $expected
     * @return void
     */
    public function testBuildContact(array $config, array $expected): void
    {
        $SUT = new InfoBuilder();
        $info = $SUT->build($config);
        $this->assertSameAssociativeArray($expected, $info->toArray());
    }

    public function providerBuildContact(): array
    {
        $common = [
            'title' => 'sample_title',
            'description' => 'sample_description',
            'version' => 'sample_version',
        ];

        return [
            'If all the elements are present, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
            ],
            'If Contact.name does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
                array_merge($common, [
                    'contact' => [
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
            ],
            'If Contact.email does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
            ],
            'If Contact.url does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
            ],
            'If Contact does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
                array_merge($common, [
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
            ],
            'If Contact.* does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [],
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
                array_merge($common, [
                    'license' => [
                        'name'=>'sample_license_name',
                        'url'=>'sample_license_url',
                    ],
                ]),
            ],

            'If License.name does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'url'=>'sample_license_url',
                    ],
                ]),
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                ]),
            ],
            'If License.url does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                    ],
                ]),
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [
                        'name'=>'sample_license_name',
                    ],
                ]),
            ],
            'If License does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                ]),
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                ]),
            ],
            'If License.* does not exist, the correct json can be output.' => [
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                    'license' => [],
                ]),
                array_merge($common, [
                    'contact' => [
                        'name' => 'sample_contact_name',
                        'email' => 'sample_contact_email',
                        'url' => 'sample_contact_url',
                    ],
                ]),
            ],
            'If License and Contacts do not exist, the correct json can be output.' => [
                array_merge($common),
                array_merge($common),
            ],
        ];
    }

    /**
     * Assert equality as an associative array.
     *
     * @param  array  $expected
     * @param  array  $actual
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
