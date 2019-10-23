<?php

declare(strict_types=1);

namespace App\EventListener;

use DateTime;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class TrackerListener
{
    public function onResponse(ResponseEvent $event): void
    {
        if ($event->getRequest()->cookies->has('uid')) {
            return;
        }

        $uuid = Uuid::uuid4()->toString();
        $cookie = Cookie::create('uid', $uuid, new DateTime('+5 years'), '/', null, false, false);
        $event->getResponse()->headers->setCookie($cookie);
    }
}
