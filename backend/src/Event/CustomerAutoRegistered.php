<?php

declare(strict_types=1);

namespace App\Event;

class CustomerAutoRegistered extends CustomerRegistered
{
    public function getName()
    {
        return 'customer.auto_registered';
    }
}
