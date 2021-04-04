<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Splash\Event\ResponseEvent;
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
$eventDispatcher->addListener('response', function (ResponseEvent $event) {
    $response = $event->getResponse();

    if ($response->isRedirection()
        || ($response->headers->has('Content-Type') && false == strpos($response->headers->get('Content-Type'), 'html'))
        || 'html' !== $event->getRequest()->getRequestFormat()
    ) {
        return;
    }

    $response->setContent($response->getContent() . 'GA CODE');
});
$eventDispatcher->addListener('response', function (ResponseEvent $event) {
    $response = $event->getResponse();
    $headers = $response->headers;

    if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
        $headers->set('Content-Length', strlen($response->getContent()));
    }
}, -255);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($eventDispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();