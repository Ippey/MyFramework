<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello/{name}', [
    'name' => 'World',
    '_controller' => function (Request $request) {
        $request->attributes->set('foo', 'bar');

        $response = render_template($request);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    },
]));
$routes->add('bye', new Route('/bye'));

return $routes;