<?php

declare(strict_types=1);

namespace App\Event;

use App\Security\Customer;
use Symfony\Contracts\EventDispatcher\Event;

class CustomerRegistered extends Event
{
    /**
     * @var Customer
     */
    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getName()
    {
        return 'customer.registered';
    }
}
