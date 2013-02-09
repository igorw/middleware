<?php

namespace Igorw\Middleware;

use Symfony\Component\HttpKernel\HttpKernelInterface;

class Stack
{
    private $specs = [];

    public function push(/*$kernelClass, $args...*/)
    {
        $this->specs[] = func_get_args();

        return $this;
    }

    public function resolve(HttpKernelInterface $app)
    {
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
