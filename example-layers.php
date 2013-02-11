<?php

use Igorw\Middleware\CallableHttpKernel;
use Igorw\Middleware\Stack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require 'vendor/autoload.php';

$app = new CallableHttpKernel(function (Request $request) {
    return new Response("Hello World!\n");
});

$stack = (new Stack())
    ->push('Igorw\Middleware\Inline', function ($app, $request, $type, $catch) {
        $response = $app->handle($request, $type, $catch);
        $response->setContent("Foo\n".$response->getContent());
        return $response;
    })
    ->push('Igorw\Middleware\Inline', function ($app, $request, $type, $catch) {
        $response = $app->handle($request, $type, $catch);
        $response->setContent("Bar\n".$response->getContent());
        return $response;
    });

$app = $stack->resolve($app);

$request = Request::create('/');
$response = $app->handle($request)->send();
$app->terminate($request, $response);
