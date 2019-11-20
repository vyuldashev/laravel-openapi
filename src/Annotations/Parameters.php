<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class Parameters
{
    /**
     * @Required()
     */
    public $factory;

    public function __construct($values)
    {
        $this->factory = class_exists($values['factory']) ? $values['factory'] : app()->getNamespace().'OpenApi\\Parameters\\'.$values['factory'];

        if (! is_a($this->factory, ParametersFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ParametersFactory');
        }
    }
}
