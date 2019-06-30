<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Support\ServiceProvider;
use Vyuldashev\LaravelOpenApi\Console\GenerateCommand;

class OpenApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerAnnotations();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/openapi.php' => config_path('openapi.php')
            ], 'openapi-config');

            $this->commands([
                GenerateCommand::class,
            ]);
        }
    }

    public function boot(): void
    {

    }

    protected function registerAnnotations(): void
    {
        $files = glob(__DIR__ . '/Annotations/*.php');

        foreach ($files as $file) {
            AnnotationRegistry::registerFile($file);
        }
    }
}
