<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;

class InfoBuilder
{
    public function build(array $config): Info
    {
        return Info::create()
            ->title($config['title'])
            ->version($config['version']);
    }
}
