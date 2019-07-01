<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\StringType;
use DomainException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Vyuldashev\LaravelOpenApi\Contracts\SchemaNormalizerInterface;

class SchemasBuilder
{
    public function build(array $schemas): array
    {
        return collect($schemas)
            ->mapWithKeys(function ($definition, $class) {
                if (is_a($definition, SchemaNormalizerInterface::class, true)) {
                    /** @var SchemaNormalizerInterface $normalizer */
                    $normalizer = resolve($definition);

                    return $normalizer->normalize();
                }

                if (is_a($definition, Model::class, true)) {
                    return [$class => $this->buildSchemaForModel($definition)];
                }

                throw new DomainException('Unexpected value');
            })
            ->values()
            ->toArray();
    }

    protected function buildSchemaForModel(string $class): Schema
    {
        /** @var Model $model */
        $model = resolve($class);

        $columns = SchemaFacade::connection($model->getConnectionName())->getColumnListing($model->getTable());
        $connection = $model->getConnection();

        $properties = collect($columns)
            ->map(static function ($column) use ($model, $connection) {
                /** @var Column $column */
                $column = $connection->getDoctrineColumn($model->getTable(), $column);
                $name = $column->getName();

                switch (get_class($column->getType())) {
                    case IntegerType::class:
                        return Schema::integer($name)->default($column->getDefault());
                    case BooleanType::class:
                        return Schema::boolean($name)->default($column->getDefault());
                    case DateType::class:
                        return Schema::string($name)->format(Schema::FORMAT_DATE)->default($column->getDefault());
                    case StringType::class:
                        return Schema::string($name)->default($column->getDefault());
                    case DecimalType::class:
                        return Schema::number($name)->format(Schema::FORMAT_FLOAT)->default($column->getDefault());
                    default:
                        throw new DomainException('No schema defined for ' . get_class($column->getType()));
                }
            })
            ->values()
            ->toArray();

        return Schema::object(class_basename($class))
            ->properties(...$properties);
    }
}
