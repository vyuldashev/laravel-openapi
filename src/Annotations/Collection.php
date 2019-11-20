<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 *
 * @Target({"CLASS", "METHOD"})
 */
class Collection
{
    /** @var string|array<string> */
    public $name;
}
