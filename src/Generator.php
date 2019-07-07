<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Contracts\Foundation\Application;
use Vyuldashev\LaravelOpenApi\Builders\ComponentsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\InfoBuilder;
use Vyuldashev\LaravelOpenApi\Builders\PathsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\ServersBuilder;

class Generator
{
    public $version = OpenApi::OPENAPI_3_0_2;

    protected $app;
    protected $config;

    public function __construct(Application $app, array $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function generate(): OpenApi
    {
        $info = $this->app[InfoBuilder::class]->build($this->config['info']);
        $servers = $this->app[ServersBuilder::class]->build($this->config['servers']);
        $paths = $this->app[PathsBuilder::class]->build();
        $components = $this->app[ComponentsBuilder::class]->build();

        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->info($info)
            ->servers(...$servers)
            ->paths(...$paths)
            ->components($components);

        return $openApi;
    }
}
