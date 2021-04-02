<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;


/**
 * render template
 *
 * @param Request $request
 * @return Response
 */
function render_template(Request $request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__ . '/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}

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

$routes->add('leap_year', new Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => 'Calendar\Controller\LeapYearController::index',
]));

return $routes;