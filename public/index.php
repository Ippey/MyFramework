<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

/** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */
$container = include __DIR__ . '/../src/container.php';
$container->setParameter('routes', include __DIR__.'/../src/app.php');
$request = Request::createFromGlobals();

/** @var \Splash\Framework $framework */
$framework = $container->get('framework');

$framework->handle($request)->send();