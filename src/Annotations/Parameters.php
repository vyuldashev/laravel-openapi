<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersNormalizerInterface;

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
    public $normalizer;

    public function __construct($values)
    {
        $this->normalizer = $values['normalizer'];

        if (!is_a($this->normalizer, ParametersNormalizerInterface::class, true)) {
            throw new InvalidArgumentException('Parameters normalizer must be instance of ParametersNormalizerInterface');
        }
    }
}
