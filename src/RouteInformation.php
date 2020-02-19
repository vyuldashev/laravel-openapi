<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi;

use Doctrine\Common\Annotations\AnnotationReader;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionMethod;
use ReflectionParameter;

class RouteInformation
{
    public $domain;
    public $method;
    public $uri;
    public $name;
    public $controller;

    public $parameters;

    /** @var array */
    public $controllerAnnotations;

    public $action;

    /** @var ReflectionParameter[] */
    public $actionParameters;

    /** @var array */
    public $actionAnnotations;

    /** @var DocBlock|null */
    public $actionDocBlock;

    public static function createFromRoute(Route $route)
    {
        return tap(new static(), static function (self $instance) use ($route): void {
            $method = collect($route->methods())
                ->map(static function ($value) {
                    return Str::lower($value);
                })
                ->filter(static function ($value) {
                    return !in_array($value, ['head', 'options'], true);
                })
                ->first();

            $actionNameParts = explode('@', $route->getActionName());

            if (count($actionNameParts) === 2) {
                [$controller, $action] = $actionNameParts;
            } else {
                [$controller] = $actionNameParts;
                $action = '__invoke';
            }

            preg_match_all('/\{(.*?)\}/', $route->uri, $parameters);
            $parameters = $parameters[1];

            if (count($parameters) > 0) {
                $parameters = collect($parameters)->map(static function ($parameter) {
                    return [
                        'name' => Str::replaceLast('?', '', $parameter),
                        'required' => ! Str::endsWith($parameter, '?'),
                    ];
                });
            }

            $reflectionMethod = new ReflectionMethod($controller, $action);

            $docComment = $reflectionMethod->getDocComment();
            $docBlock = $docComment ? DocBlockFactory::createInstance()->create($docComment) : null;

            $reader = new AnnotationReader();
            $controllerAnnotations = $reader->getClassAnnotations($reflectionMethod->getDeclaringClass());
            $actionAnnotations = $reader->getMethodAnnotations($reflectionMethod);

            $instance->domain = $route->domain();
            $instance->method = $method;
            $instance->uri = Str::start($route->uri(), '/');
            $instance->name = $route->getName();
            $instance->controller = $controller;
            $instance->parameters = $parameters;
            $instance->controllerAnnotations = $controllerAnnotations;
            $instance->action = $action;
            $instance->actionParameters = $reflectionMethod->getParameters();
            $instance->actionAnnotations = $actionAnnotations;
            $instance->actionDocBlock = $docBlock;
        });
    }
}
