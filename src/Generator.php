<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Support\Arr;
use Vyuldashev\LaravelOpenApi\Builders\ComponentsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\InfoBuilder;
use Vyuldashev\LaravelOpenApi\Builders\PathsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\ServersBuilder;
use Vyuldashev\LaravelOpenApi\Builders\TagsBuilder;

class Generator
{
    public $version = OpenApi::OPENAPI_3_0_2;

    protected $config;
    protected $infoBuilder;
    protected $serversBuilder;
    protected $tagsBuilder;
    protected $pathsBuilder;
    protected $componentsBuilder;

    public function __construct(
        array $config,
        InfoBuilder $infoBuilder,
        ServersBuilder $serversBuilder,
        TagsBuilder $tagsBuilder,
        PathsBuilder $pathsBuilder,
        ComponentsBuilder $componentsBuilder
    )
    {
        $this->config = $config;
        $this->infoBuilder = $infoBuilder;
        $this->serversBuilder = $serversBuilder;
        $this->tagsBuilder = $tagsBuilder;
        $this->pathsBuilder = $pathsBuilder;
        $this->componentsBuilder = $componentsBuilder;
    }

    public function generate(): OpenApi
    {
        $info = $this->infoBuilder->build(Arr::get($this->config, 'info', []));
        $servers = $this->serversBuilder->build(Arr::get($this->config, 'servers', []));
        $tags = $this->tagsBuilder->build(Arr::get($this->config, 'tags', []));
        $paths = $this->pathsBuilder->build();
        $components = $this->componentsBuilder->build();

        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->info($info)
            ->servers(...$servers)
            ->tags(...$tags)
            ->security(...Arr::get($this->config, 'security', []))
            ->paths(...$paths)
            ->components($components);

        return $openApi;
    }
}
