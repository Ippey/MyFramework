<?php


namespace Calendar\Controller;


use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    /**
     * @param FlattenException $exception
     * @return Response
     */
    public function exception (FlattenException $exception): Response
    {
        $message = 'Something went wrong. (' . $exception->getMessage() . ')';

        return new Response($message, $exception->getStatusCode());
    }
}