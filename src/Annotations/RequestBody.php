<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class RequestBody
{
    public $normalizer;
}
