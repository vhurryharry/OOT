<?php

declare(strict_types=1);

namespace App\Entity;

use RandomLib\Factory;

class Order extends Cart
{
    const PENDING_CONFIRMATION = 'pending_confirmation';

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $status = self::PENDING_CONFIRMATION;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var Payment
     */
    protected $payment;

    public function __construct(string $customerId)
    {
        $this->number = $this->generateNumber();
        $this->customerId = $customerId;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }

    protected function generateNumber(): string
    {
        $generator = (new Factory())->getLowStrengthGenerator();

        return $generator->generateString(10, '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ');
    }
}
