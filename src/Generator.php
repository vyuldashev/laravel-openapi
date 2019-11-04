<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Vyuldashev\LaravelOpenApi\Builders\ComponentsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\InfoBuilder;
use Vyuldashev\LaravelOpenApi\Builders\PathsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\ServersBuilder;

class Generator
{
    public $version = OpenApi::OPENAPI_3_0_2;

    protected $config;
    protected $infoBuilder;
    protected $serversBuilder;
    protected $pathsBuilder;
    protected $componentsBuilder;

    public function __construct(
        array $config,
        InfoBuilder $infoBuilder,
        ServersBuilder $serversBuilder,
        PathsBuilder $pathsBuilder,
        ComponentsBuilder $componentsBuilder
    )
    {
        $this->config = $config;
        $this->infoBuilder = $infoBuilder;
        $this->serversBuilder = $serversBuilder;
        $this->pathsBuilder = $pathsBuilder;
        $this->componentsBuilder = $componentsBuilder;
    }

    public function generate(): OpenApi
    {
        $info = $this->infoBuilder->build($this->config['info']);
        $servers = $this->serversBuilder->build($this->config['servers']);
        $paths = $this->pathsBuilder->build();
        $components = $this->componentsBuilder->build();

        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->info($info)
            ->servers(...$servers)
            ->paths(...$paths)
            ->components($components);

        return $openApi;
    }
}
