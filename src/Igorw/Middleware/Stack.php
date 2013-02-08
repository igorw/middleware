<?php

namespace Igorw\Middleware;

use Symfony\Component\HttpKernel\HttpKernelInterface;

class Stack
{
    private $app;
    private $specs = [];

    public function __construct(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    public function push(/*$kernelClass, $args...*/)
    {
        $this->specs[] = func_get_args();
    }

    public function resolve()
    {
        $app = $this->app;
        $specs = array_reverse($this->specs);

        $middlewares = [$app];

        foreach ($specs as $spec) {
            $args = $spec;
            $kernelClass = array_shift($args);
            array_unshift($args, $app);

            $reflection = new \ReflectionClass($kernelClass);
            $app = $reflection->newInstanceArgs($args);
            array_unshift($middlewares, $app);
        }

        return new StackedHttpKernel($app, $middlewares);
    }
}
