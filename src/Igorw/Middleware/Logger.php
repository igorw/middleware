<?php

namespace Igorw\Middleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Logger implements HttpKernelInterface
{
    private $app;
    private $logger;

    public function __construct(HttpKernelInterface $app, LoggerInterface $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $response = $this->app->handle($request, $type, $catch);

        $this->logger->info(sprintf('%s "%s %s %s" %d',
            $request->getHost(),
            $request->getMethod(),
            $request->getRequestUri(),
            $request->server->get('SERVER_PROTOCOL'),
            $response->getStatusCode()));

        return $response;
    }
}
