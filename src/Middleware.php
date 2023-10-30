<?php

namespace Vyuldashev\LaravelOpenApi;

use Closure;
use Illuminate\Support\Collection;

class Middleware
{
    private Collection $middlewares;

    private mixed $eventData = null;

    public function __construct(array $middlewares)
    {
        $this->middlewares = collect($middlewares);
    }

    public static function make(array $middlewares): self
    {
        return new static($middlewares);
    }

    public function using(string $type): self
    {
        $this->middlewares = $this->middlewares->filter(fn ($middleware) => is_a($middleware, $type, true));

        return $this;
    }

    public function send(mixed $eventData): self
    {
        $this->eventData = $eventData;

        return $this;
    }

    public function through(Closure $closure): mixed
    {
        $input = $this->eventData;

        foreach ($this->middlewares as $middleware) {
            $input = $closure(app($middleware), $input);
        }

        return $input;
    }

    public function emit(Closure $closure): void
    {
        foreach ($this->middlewares as $middleware) {
            $closure(app($middleware));
        }
    }
}
