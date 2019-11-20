<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class Response
{
    /**
     * @Required()
     */
    public $factory;

    public $statusCode;

    public $description;

    public function __construct($values)
    {
        $this->factory = class_exists($values['factory']) ? $values['factory'] : app()->getNamespace().'OpenApi\\Responses\\'.$values['factory'];

        if (! is_a($this->factory, ResponseFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ResponseFactory');
        }

        $this->statusCode = isset($values['statusCode']) ? (int) $values['statusCode'] : null;
        $this->description = $values['description'] ?? null;
    }
}
