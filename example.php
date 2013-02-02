<?php

use Igorw\Middleware\CallableHttpKernel;
use Igorw\Middleware\Stack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;

require 'vendor/autoload.php';

$app = new CallableHttpKernel(function (Request $request) {
    return new Response("Hello World!\n");
});

$stack = new Stack($app);
$stack->push('Igorw\Middleware\Logger', new Monolog\Logger('app'));
$stack->push('Symfony\Component\HttpKernel\HttpCache\HttpCache', new Store(__DIR__.'/cache'));

$request = Request::create('/');
$response = $stack->resolve()->handle($request);

echo (string) $response;
