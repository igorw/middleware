<?php

use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function () {
    return "Hello World!\n";
});

$app = new Igorw\Middleware\Logger(
    $app,
    new Monolog\Logger('app')
);

$request = Request::create('/');
$response = $app->handle($request)->send();
