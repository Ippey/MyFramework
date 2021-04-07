<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$routes = include __DIR__.'/../src/app.php';
$container = include __DIR__ . '/../src/container.php';

$request = Request::createFromGlobals();

/** @var \Splash\Framework $framework */
$framework = $container->get('framework');

$framework->handle($request)->send();