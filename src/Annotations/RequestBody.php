<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyNormalizerInterface;

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
    public $normalizer;

    public function __construct($values)
    {
        $this->normalizer = $values['normalizer'];

        if (!is_a($this->normalizer, RequestBodyNormalizerInterface::class, true)) {
            throw new InvalidArgumentException('RequestBody normalizer must be instance of RequestBodyNormalizerInterface');
        }
    }
}
