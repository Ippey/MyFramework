<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Splash\EventListener\ContentLengthListener;
use Splash\EventListener\GoogleListener;
use Splash\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpCache\Esi;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();
$requestStack = new \Symfony\Component\HttpFoundation\RequestStack();
$routes = include __DIR__ . '/../src/app.php';

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$eventDispatcher = new EventDispatcher();
$eventDispatcher->addSubscriber(new \Symfony\Component\HttpKernel\EventListener\RouterListener($matcher, $requestStack));
$eventDispatcher->addSubscriber(new GoogleListener());
$eventDispatcher->addSubscriber(new ContentLengthListener());

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($eventDispatcher, $controllerResolver, $requestStack, $argumentResolver);
$framework = new HttpCache(
    $framework,
    new Store(__DIR__ . '/../cache'),
    new Esi(),
    ['debug' => true]
);

$response = $framework->handle($request);

$response->send();