<?php

declare(strict_types=1);

namespace App\Security;

class RequestId
{
    public static function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}
