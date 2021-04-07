<?php

namespace Splash\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Splash\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends TestCase
{
    /**
     * test not found
     */
    public function testNotFoundHandling()
    {
        $this->expectException(NotFoundHttpException::class);
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());
        $response = $framework->handle(new Request());
    }

    /**
     * test internal server error
     */
    public function testErrorHandling()
    {
        $this->expectException(NotFoundHttpException::class);
        $framework = $this->getFrameworkForException(new RuntimeException());

        $response = $framework->handle(new Request());

        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @param $exception
     * @return Framework
     */
    private function getFrameworkForException($exception): Framework
    {
        $eventDispatcher = $this->createMock(EventDispatcher::class);

        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);
        // use getMock() on PHPUnit 5.3 or below
        // $matcher = $this->getMock(Routing\Matcher\UrlMatcherInterface::class);

        $requestStack = $this->createMock(RequestStack::class);
        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $controllerResolver
            ->method('getController')
            ->will($this->returnValue(false));
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($eventDispatcher, $controllerResolver, $requestStack, $argumentResolver);
    }
}
