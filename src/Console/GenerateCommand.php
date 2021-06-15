<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Console;

use GoldSpecDigital\ObjectOrientedOAS\Exceptions\ValidationException;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;
use Vyuldashev\LaravelOpenApi\Generator;

class GenerateCommand extends Command
{
    protected $signature = 'openapi:generate {collection=default} {--format=JSON} {--filePath=}';
    protected $description = 'Generate OpenAPI specification';

    protected string $format;
    protected string $filePath;
    protected OpenApi $openApi;

    public function handle(Generator $generator): void
    {
        $this->setFormat();
        $this->setFilePath();

        $collectionExists = collect(config('openapi.collections'))->has($this->argument('collection'));

        if (! $collectionExists) {
            $this->error('Collection "'.$this->argument('collection').'" does not exist.');
            return;
        }

        $this->openApi = $generator->generate($this->argument('collection'));

        $this->line($this->getContents());

        if ($this->filePath) {
            $this->saveToFile();
        }
    }

    protected function saveToFile(): void
    {
        Storage::put($this->filePath.'/'.$this->generateFileName(), $this->getContents());
        $this->info(__('OpenAPI generated in :FORMAT format saved to :filePath', ['format' => $this->format, 'filePath' => storage_path($this->filePath)]));
    }

    protected function setFormat(): void
    {
        $this->format = $this->option('format') ?? 'JSON';
    }

    protected function setFilePath(): void
    {
        $this->filePath = $this->option('filePath') ?? 'openapi';
    }

    protected function getContents(): string
    {
        return match($this->format) {
            'JSON' => $this->openApi->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'YAML' => Yaml::dump($this->openApi->toArray(), 6),
        };
    }

    private function generateFileName(): string
    {
        return collect([
            Str::of(config('app.name'))->slug(),
            '-openapi-'.now()->toDateString(),
            '.'.strtolower($this->format),
        ])->implode('');
    }
}
