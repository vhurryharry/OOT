<?php

declare(strict_types=1);

namespace App\Entity;

use Money\Currency;
use Money\Money;

class Cart
{
    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var array
     */
    protected $items = [];

    public function __construct()
    {
        $this->currency = new Currency('USD');
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function getGrandTotal(): Money
    {
        $grandTotal = new Money(0, $this->currency);

        foreach ($this->items as $item) {
            $grandTotal = $grandTotal->add(new Money($item['price'], $this->currency));
        }

        return $grandTotal;
    }
}
