<?php

declare(strict_types=1);

namespace App\Listener;

use App\Security\RequestId;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestIdentifier
{
    public function identifyRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->headers->has('X-Request-ID')) {
            $request->headers->set('X-Request-ID', RequestId::generate());
        }

        $request->attributes->set('requestId', $request->headers->get('X-Request-ID'));
    }

    public function identifyResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $requestId = $request->headers->get('X-Request-ID');

        if ($requestId) {
            $response->headers->set('X-Request-ID', $requestId);
        }
    }
}
