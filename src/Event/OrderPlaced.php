<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

class OrderPlaced extends Event
{
    /**
     * @var Order
     */
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getName()
    {
        return 'order.placed';
    }
}
