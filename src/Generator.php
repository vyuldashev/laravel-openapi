<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Contracts\Foundation\Application;
use Vyuldashev\LaravelOpenApi\Builders\PathsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\SchemasBuilder;

class Generator
{
    public $version = OpenApi::OPENAPI_3_0_2;
    /** @var Info */
    public $info;
    /** @var Server[] */
    public $servers;
    public $schemas = [];

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function generate(): OpenApi
    {
        $paths = $this->app[PathsBuilder::class]->build();

        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->info($this->info)
            ->servers(...$this->servers)
            ->paths(...$paths);

        if (count($this->schemas) > 0) {
            $schemas = $this->app[SchemasBuilder::class]->build($this->schemas);

            $openApi = $openApi->components(
                Components::create()->schemas(...$schemas)
            );
        }

        return $openApi;
    }

    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }

    public function setInfo(Info $info)
    {
        $this->info = $info;

        return $this;
    }

    public function setServers(array $servers)
    {
        $this->servers = $servers;

        return $this;
    }

    public function setSchemas($schemas)
    {
        $this->schemas = $schemas;

        return $this;
    }
}
