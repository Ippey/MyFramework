<?php

namespace Splash;

use Exception;
use Splash\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework implements HttpKernelInterface
{
    /** @var UrlMatcherInterface */
    private UrlMatcherInterface $matcher;
    /** @var ControllerResolverInterface */
    private ControllerResolverInterface $controllerResolver;
    /** @var ArgumentResolverInterface */
    private ArgumentResolverInterface $argumentResolver;
    /** @var EventDispatcherInterface */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * Framework constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param UrlMatcherInterface $matcher
     * @param ControllerResolverInterface $controllerResolver
     * @param ArgumentResolverInterface $argumentResolver
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            return new Response('Not Found', 404);
        } catch (Exception $e) {
            return new Response('An error occurred', 500);
        }

        // dispatch response events.
        $this->eventDispatcher->dispatch(new ResponseEvent($response, $request), 'response');

        return $response;
    }
}