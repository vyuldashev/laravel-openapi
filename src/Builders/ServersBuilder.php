<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;

class ServersBuilder
{
    /**
     * @param array $config
     * @return Server[]
     */
    public function build(array $config): array
    {
        return collect($config)
            ->map(static fn(array $server) => Server::create()->url($server['url']))
            ->toArray();
    }
}
