<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class Response
{
    public $factory;

    public $ref;

    public $statusCode;

    public function __construct($values)
    {
        if (isset($values['factory'])) {
            $this->factory = class_exists($values['factory']) ? $values['factory'] : app()->getNamespace() . 'OpenApi\\Responses\\' . $values['factory'];

            if (!is_a($this->factory, ResponseFactory::class, true)) {
                throw new InvalidArgumentException('Factory class must be instance of ' . ResponseFactory::class);
            }

            return;
        }

        if (isset($values['ref'], $values['statusCode'])) {
            $this->ref = $values['ref'];
            $this->statusCode = $values['statusCode'];

            return;
        }

        throw new InvalidArgumentException('Either `factory` or `ref` should be used.');
    }
}
