<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class RequestBody
{
    /**
     * @Required()
     */
    public $factory;

    public function __construct($values)
    {
        $this->factory = class_exists($values['factory']) ? $values['factory'] : app()->getNamespace().'OpenApi\\RequestBodies\\'.$values['factory'];

        if (! is_a($this->factory, RequestBodyFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of RequestBodyFactory');
        }
    }
}
