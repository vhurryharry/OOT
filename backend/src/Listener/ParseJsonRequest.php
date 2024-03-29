<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class ParseJsonRequest
{
    public function parseRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $content = (string) $request->getContent();

        if ($request->getContentType() != 'json') {
            return;
        }

        if (empty($content)) {
            return;
        }

        $request->request->replace(json_decode($content, true));
    }
}
