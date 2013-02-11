<?php

namespace Igorw\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Inline implements HttpKernelInterface
{
    private $app;
    private $callable;

    public function __construct(HttpKernelInterface $app, callable $callable)
    {
        $this->app = $app;
        $this->callable = $callable;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        return call_user_func($this->callable, $this->app, $request, $type, $catch);
    }
}
