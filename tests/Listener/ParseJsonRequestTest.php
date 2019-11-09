<?php

declare(strict_types=1);

namespace App\Listener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ParseJsonRequestTest extends TestCase
{
    public function testIsParsingJsonRequest(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);

        $request = new Request([], [], [], [], [], [], '{"foo": "bar"}');
        $request->headers->set('Content-Type', 'application/json');

        $event = new RequestEvent($kernel->reveal(), $request, 0);

        $listener = new ParseJsonRequest();
        $listener->parseRequest($event);

        $this->assertEquals('bar', $event->getRequest()->get('foo'));
    }

    public function testIsIgnoringEmptyRequest(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);

        $request = new Request([], [], [], [], [], [], '');
        $request->headers->set('Content-Type', 'application/json');

        $event = new RequestEvent($kernel->reveal(), $request, 0);

        $listener = new ParseJsonRequest();
        $listener->parseRequest($event);

        $this->assertNull($event->getRequest()->get('foo'));
    }

    public function testIsIgnoringNonJsonRequest(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);

        $request = new Request([], [], [], [], [], [], '{"foo": "bar"}');
        $request->headers->set('Content-Type', 'application/xml');

        $event = new RequestEvent($kernel->reveal(), $request, 0);

        $listener = new ParseJsonRequest();
        $listener->parseRequest($event);

        $this->assertNull($event->getRequest()->get('foo'));
        $this->assertNull($event->getResponse());
    }
}
