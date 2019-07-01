<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Support\ServiceProvider;
use Vyuldashev\LaravelOpenApi\Console\GenerateCommand;
use Vyuldashev\LaravelOpenApi\Console\InstallCommand;

class OpenApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Console/stubs/OpenApiServiceProvider.stub' => app_path('Providers/OpenApiServiceProvider.php'),
            ], 'openapi-provider');
        }

        $this->registerAnnotations();
    }

    public function register(): void
    {
        $this->commands([
            GenerateCommand::class,
            InstallCommand::class,
        ]);
    }

    protected function registerAnnotations(): void
    {
        $files = glob(__DIR__ . '/Annotations/*.php');

        foreach ($files as $file) {
            AnnotationRegistry::registerFile($file);
        }
    }
}
