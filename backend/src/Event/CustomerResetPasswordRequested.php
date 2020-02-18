<?php

declare(strict_types=1);

namespace App\Event;

use App\Security\Customer;
use Symfony\Contracts\EventDispatcher\Event;

class CustomerResetPasswordRequested extends Event
{
    /**
     * @var Customer
     */
    protected $customer;
    
    protected $resetUri;    

    public function __construct(Customer $customer, $resetUri)
    {
        $this->customer = $customer;
        $this->resetUri = $resetUri;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getResetUri()
    {
        return $this->resetUri;
    }

    public function getName()
    {
        return 'customer.resetPasswordRequested';
    }
}
