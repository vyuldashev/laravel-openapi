<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class Response
{
    public $normalizer;
}
