<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;


class SchemaFactoryMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-schema';
    protected $description = 'Create a new Schema factory class';
    protected $type = 'Schema';
    
    protected function buildClass($name)
    {
        $output = parent::buildClass($name);
        $output = str_replace('DummySchema', Str::replaceLast('Schema', '', class_basename($name)), $output);
        
        if ($model = $this->option('model')) {
            return $this->buildModel($output, $model);
        }
        
        return $output;
    }
    
    protected function buildModel($output, $model)
    {
        $appVersion = explode('.', app()::VERSION);
        $namespace = $appVersion[0] >= 8 ? $this->laravel->getNamespace() . 'Models\\' : $this->laravel->getNamespace();
        $model = Str::start($model, $namespace);
        
        if (!is_a($model, Model::class, true)) {
            throw new InvalidArgumentException('Invalid model');
        }
        
        /** @var Model $model */
        $model = app($model);
        
        $columns = SchemaFacade::getColumns($model->getTable());
        
        $definition = 'return Schema::object(\'' . class_basename($model) . '\')' . PHP_EOL;
        $definition .= '            ->properties(' . PHP_EOL;
        
        $properties = collect($columns)
            ->map(static function (array $column) {
                $columnType = $column['type_name'];
                $default = $column['default'] ?? null;
                $notNull = ! $column['nullable'];
                $name = $column['name'];
                
                switch ($columnType) {
                    case 'integer':
                    case 'bigint':
                    case 'smallint':
                        $format = 'Schema::integer(%s)->default(%s)';
                        $args = [$name, $notNull ? (int)$default : null];
                        break;
                    case 'boolean':
                        $format = 'Schema::boolean(%s)->default(%s)';
                        $args = [$name, $notNull ? $default : null];
                        break;
                    case 'date':
                    case 'date_immutable':
                        $format = 'Schema::string(%s)->format(Schema::FORMAT_DATE)->default(%s)';
                        $args = [$name, $notNull ? $default : null];
                        break;
                    case 'datetime':
                    case 'datetime_immutable':
                    case 'datetimetz':
                    case 'datetimetz_immutable':
                        $format = 'Schema::string(%s)->format(Schema::FORMAT_DATE_TIME)->default(%s)';
                        $args = [$name, $notNull ? $default : null];
                        break;
                    case 'decimal':
                    case 'float':
                        $format = 'Schema::number(%s)->format(Schema::FORMAT_FLOAT)->default(%s)';
                        $args = [$name, $notNull ? (float)$default : null];
                        break;
                    case 'array':
                    case 'json':
                        $format = 'Schema::array(%s)->default(%s)';
                        $args = [$name, $notNull ? (array)$default : null];
                        break;
                    case 'guid':
                    case 'uuid':
                        $format = 'Schema::string(%s)->format(Schema::FORMAT_UUID)->default(%s)';
                        $args = [$name, $notNull ? (array)$default : null];
                        break;
                    case 'binary':
                        $format = 'Schema::string(%s)->format(Schema::FORMAT_BINARY)->default(%s)';
                        $args = [$name, $notNull ? $default : null];
                        break;
                    default:
                        $format = 'Schema::string(%s)->default(%s)';
                        $args = [$name, $default];
                        break;
                }
                
                $args = array_map(static function ($value) {
                    if ($value === null) {
                        return 'null';
                    }
                    
                    if (is_numeric($value)) {
                        return $value;
                    }
                    
                    return '\'' . $value . '\'';
                }, $args);
                
                $indentation = str_repeat('    ', 4);
                
                return sprintf($indentation . $format, ...$args);
            })
            ->implode(',' . PHP_EOL);
        
        $definition .= $properties . PHP_EOL;
        $definition .= '            );';
        
        return str_replace('DummyDefinition', $definition, $output);
    }
    
    protected function getStub(): string
    {
        if ($this->option('model')) {
            return __DIR__ . '/stubs/schema.model.stub';
        }
        
        return __DIR__ . '/stubs/schema.stub';
    }
    
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\OpenApi\Schemas';
    }
    
    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);
        
        if (Str::endsWith($name, 'Schema')) {
            return $name;
        }
        
        return $name . 'Schema';
    }
    
    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model class schema being generated for'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the factory already exists'],
        ];
    }
}
