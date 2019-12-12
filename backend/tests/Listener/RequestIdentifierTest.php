<?php

declare(strict_types=1);

namespace App\Listener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestIdentifierTest extends TestCase
{
    public function testIsUsingExistingRequestId(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);

        $request = new Request();
        $request->headers->set('X-Request-ID', 'foobar');

        $event = new RequestEvent($kernel->reveal(), $request, 0);

        $listener = new RequestIdentifier();
        $listener->identifyRequest($event);

        $this->assertEquals('foobar', $request->headers->get('X-Request-ID'));
    }

    public function testIsGeneratingRequestIdWhenMissing(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = new Request();
        $event = new RequestEvent($kernel->reveal(), $request, 0);

        $listener = new RequestIdentifier();
        $listener->identifyRequest($event);

        $this->assertRegexp('/[a-zA-Z0-9]+/', $request->headers->get('X-Request-ID'));
    }

    public function testIsSendingRequestIdWithResponse(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);

        $request = new Request();
        $request->headers->set('X-Request-ID', 'foobar');

        $response = new Response();

        $event = new ResponseEvent($kernel->reveal(), $request, 0, $response);

        $listener = new RequestIdentifier();
        $listener->identifyResponse($event);

        $this->assertEquals('foobar', $request->headers->get('X-Request-ID'));
        $this->assertEquals('foobar', $response->headers->get('X-Request-ID'));
    }
}
