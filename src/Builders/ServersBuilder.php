<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;

class ServersBuilder
{
    public function build(array $config): array
    {
        return collect($config)
            ->map(static function (array $server) {
                return Server::create()->url($server['url']);
            })
            ->toArray();
    }
}
