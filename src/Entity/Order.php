<?php

declare(strict_types=1);

namespace App\Entity;

use RandomLib\Factory;

class Order
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

    public function __construct()
    {
        $this->number = $this->generateNumber();
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): void
    {
        $this->customerId = $customerId;
    }

    protected function generateNumber(): string
    {
        $generator = (new Factory())->getLowStrengthGenerator();

        return $generator->generateString(10, '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ');
    }
}
