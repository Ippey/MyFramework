<?php


namespace Splash\Event;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseEvent
{
    /**
     * @var Response
     */
    private Response $response;
    /**
     * @var Request
     */
    private Request $request;

    /**
     * ResponseEvent constructor.
     * @param Response $response
     * @param Request $request
     */
    public function __construct(Response $response, Request $request)
    {

        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

}