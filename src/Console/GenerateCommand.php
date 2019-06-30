<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Console;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class GenerateCommand extends Command
{
    protected $signature = 'openapi:generate';
    protected $description = 'Generate OpenAPI specification';

    public function handle(OpenApi $openApi): void
    {
        echo $openApi->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
