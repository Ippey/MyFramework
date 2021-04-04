<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Splash\Event\ResponseEvent;
use Splash\EventListener\ContentLengthListener;
use Splash\EventListener\GoogleListener;
use Splash\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../src/app.php';

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$eventDispatcher = new EventDispatcher();
$eventDispatcher->addListener('response', [new GoogleListener(), 'onResponse']);
$eventDispatcher->addListener('response', [new ContentLengthListener(), 'onResponse'],  -255);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($eventDispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();