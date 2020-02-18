<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class Operation
{
    /** @var string */
    public $id;

    /** @var array<string> */
    public $tags;

    /** @var string */
    public $method;
}
