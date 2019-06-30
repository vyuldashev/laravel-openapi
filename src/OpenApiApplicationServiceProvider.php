<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Vyuldashev\LaravelOpenApi\Annotations\Operation;
use Vyuldashev\LaravelOpenApi\Annotations\PathItem;

class OpenApiApplicationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Container::routes($this->routes());
        Container::schemas($this->schemas());

        $this->app->singleton(OpenApi::class, function () {
            $openApi = OpenApi::create()
                ->openapi(OpenApi::OPENAPI_3_0_2)
                ->info($this->info())
                ->servers(...$this->servers())
                ->paths(...Container::$paths)
                ->components(
                    Components::create()->schemas(...Container::$schemas)
                );

            return $openApi;
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

    private function routes(): array
    {
        return collect($this->app[Router::class]->getRoutes())
            ->map(static function (Route $route) {
                return RouteInformation::createFromRoute($route);
            })
            ->filter(static function (RouteInformation $route) {
                $pathItem = collect($route->controllerAnnotations)->first(static function ($annotation) {
                    return $annotation instanceof PathItem;
                });

                $operation = collect($route->actionAnnotations)->first(static function ($annotation) {
                    return $annotation instanceof Operation;
                });

                return $pathItem && $operation;
            })
            ->toArray();
    }
}
