<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class OpenApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/openapi.php' => config_path('openapi.php'),
            ], 'openapi-config');
        }

        $this->registerAnnotations();

        $this->app->singleton(Generator::class, static function ($app) {
            $config = config('openapi');

            return new Generator($app, $config);
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/openapi.php', 'openapi'
        );

        $this->commands([
            Console\GenerateCommand::class,
            Console\ParametersNormalizerMakeCommand::class,
            Console\RequestBodyNormalizerMakeCommand::class,
            Console\ResponseNormalizerMakeCommand::class,
            Console\SchemaMakeCommand::class,
        ]);
    }

    protected function registerAnnotations(): void
    {
        $files = glob(__DIR__ . '/Annotations/*.php');

        foreach ($files as $file) {
            AnnotationRegistry::registerFile($file);
        }
    }

    public function provides(): array
    {
        return [
            Generator::class,
        ];
    }
}
