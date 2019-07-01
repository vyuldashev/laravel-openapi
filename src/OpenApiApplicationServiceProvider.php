<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Support\ServiceProvider;

class OpenApiApplicationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(Generator::class, function () {
            return (new Generator())
                ->setVersion(OpenApi::OPENAPI_3_0_2)
                ->setInfo($this->info())
                ->setServers($this->servers())
                ->setSchemas($this->schemas());
        });
    }

    protected function info(): Info
    {
        return Info::create()
            ->title(config('app.name'))
            ->version('1.0.0');
    }

    /**
     * @return Server[]
     */
    protected function servers(): array
    {
        return [
            Server::create()->url(url()->to('/')),
        ];
    }

    /**
     * @return Schema[]
     */
    protected function schemas(): array
    {
        return [];
    }
}
