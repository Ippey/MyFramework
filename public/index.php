<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Splash\Framework;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

/** @var ContainerBuilder $container */
$container = include __DIR__ . '/../src/container.php';
$container->setParameter('routes', include __DIR__ . '/../src/app.php');
$request = Request::createFromGlobals();

/** @var Framework $framework */
$framework = $container->get('framework');

$framework->handle($request)->send();