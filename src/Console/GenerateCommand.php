<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\Command;
use Vyuldashev\LaravelOpenApi\Generator;

class GenerateCommand extends Command
{
    protected $signature = 'openapi:generate';
    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        echo $generator
            ->generate()
            ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
