<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;
use Illuminate\Support\Arr;

class TagsBuilder
{
    /**
     * @param array $config
     * @return Tag[]
     */
    public function build(array $config): array
    {
        return collect($config)
            ->map(static function (array $tag) {
                return Tag::create()
                    ->name($tag['name'])
                    ->description(Arr::get($tag, 'description'));
            })
            ->toArray();
    }
}
