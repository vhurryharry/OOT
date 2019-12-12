<?php

declare(strict_types=1);

namespace App\Controller\Output;

use App\Entity\Cart;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

class OrderOutput
{
    /**
     * @var IntlMoneyFormatter
     */
    protected $moneyFormatter;

    public function __construct()
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $this->moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());
    }

    public function fromCart(Cart $cart): array
    {
        return [
            'items' => $this->fromItems($cart),
            'grandTotal' => $this->moneyFormatter->format($cart->getGrandTotal()),
        ];
    }

    public function fromItems(Cart $cart): array
    {
        $output = [];

        foreach ($cart->getItems() as $item) {
            $output[] = array_merge($item, [
                'unitPrice' => $this->moneyFormatter->format(
                    new Money($item['price'], $cart->getCurrency()),
                ),
            ]);
        }

        return $output;
    }
}
