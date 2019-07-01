<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    use DetectsApplicationNamespace;

    protected $signature = 'openapi:install';
    protected $description = 'Install OpenAPI';

    public function handle(): void
    {
        $this->comment('Publishing OpenAPI Service Provider...');

        $this->callSilent('vendor:publish', ['--tag' => 'openapi-provider']);

        $this->registerOpenApiServiceProvider();
    }

    /**
     * Register the OpenAPI service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerOpenApiServiceProvider(): void
    {
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());
        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\AppServiceProvider::class," . PHP_EOL,
            "{$namespace}\\Providers\AppServiceProvider::class," . PHP_EOL . "        {$namespace}\Providers\OpenApiServiceProvider::class," . PHP_EOL,
            file_get_contents(config_path('app.php'))
        ));
    }

    /**
     * Set the proper application namespace on the installed files.
     *
     * @return void
     */
    protected function setAppNamespace(): void
    {
        $namespace = $this->getAppNamespace();
        $this->setAppNamespaceOn(app_path('Providers/OpenApiServiceProvider.php'), $namespace);
    }

    /**
     * Set the namespace on the given file.
     *
     * @param string $file
     * @param string $namespace
     * @return void
     */
    protected function setAppNamespaceOn($file, $namespace): void
    {
        file_put_contents($file, str_replace(
            'App\\',
            $namespace,
            file_get_contents($file)
        ));
    }
}
