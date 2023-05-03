<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;
use Illuminate\Support\Arr;

class TagsBuilder
{
    /**
     * @param  array  $config
     * @return Tag[]
     */
    public function build(array $config): array
    {
        return collect($config)
            ->map(static function (array $tag) {
                $externalDocs = null;

                if (Arr::has($tag, 'externalDocs')) {
                    $externalDocs = ExternalDocs::create($tag['name'])
                        ->description(Arr::get($tag, 'externalDocs.description'))
                        ->url(Arr::get($tag, 'externalDocs.url'));
                }

                return Tag::create()
                    ->name($tag['name'])
                    ->description(Arr::get($tag, 'description'))
                    ->externalDocs($externalDocs);
            })
            ->toArray();
    }
}
