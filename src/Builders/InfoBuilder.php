<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use Illuminate\Support\Arr;

class InfoBuilder
{
    public function build(array $config): Info
    {
        $info = Info::create()
            ->title(Arr::get($config, 'title'))
            ->description(Arr::get($config, 'description'))
            ->version(Arr::get($config, 'version'));

        $extensions = $config['extensions'] ?? [];

        foreach ($extensions as $key => $value) {
            $info->x($key, $value);
        }

        return $info;
    }
}
