<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class LeapYearController
{
    public function index($year)
    {
        if (is_leap_year($year)) {
            return new Response('Yep, this is a leap year.');
        }

        return new Response('Nope, this is not a leap year.');
    }
}

function is_leap_year($year = null)
{
    if (null === $year) {
        $year = date('Y');
    }

    return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
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
    '_controller' => 'LeapYearController::index',
]));

return $routes;