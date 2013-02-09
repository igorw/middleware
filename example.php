<?php

use Igorw\Middleware\CallableHttpKernel;
use Igorw\Middleware\Stack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;

require 'vendor/autoload.php';

$app = new CallableHttpKernel(function (Request $request) {
    return (new Response("Hello World!\n"))
        ->setCache(['s_maxage' => 20]);
});

$stack = (new Stack())
    ->push('Igorw\Middleware\Logger', new Monolog\Logger('app'))
    ->push('Symfony\Component\HttpKernel\HttpCache\HttpCache', new Store(__DIR__.'/cache'));

$app = $stack->resolve($app);

$request = Request::create('/');
$response = $app->handle($request)->send();
$app->terminate($request, $response);
