<?php

use Igorw\Middleware\Stack;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function () {
    return "Hello World!\n";
});

$app->finish(function () {
    echo "Silex finish event fired.\n";
});

$stack = new Stack($app);
$stack->push('Igorw\Middleware\Logger', new Monolog\Logger('app'));

$app = $stack->resolve();

$request = Request::create('/');
$response = $app->handle($request)->send();
$app->terminate($request, $response);
