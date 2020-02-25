<?php

declare(strict_types=1);

namespace App\Event;

use App\Security\Customer;
use Symfony\Contracts\EventDispatcher\Event;

class CustomerConfirmationPended extends Event
{
    /**
     * @var Customer
     */
    protected $customer;

    protected $confirmationUri;

    public function __construct(Customer $customer, string $confirmationUri)
    {
        $this->customer = $customer;
        $this->confirmationUri = $confirmationUri;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getConfirmationUri()
    {
        return $this->confirmationUri;
    }

    public function getName()
    {
        return 'customer.confirmationPended';
    }
}
