<?php

declare(strict_types=1);

namespace App\Controller\Output;

use App\Entity\Cart;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use NumberFormatter;

class OrderOutput
{
    public function fromCart(Cart $cart): array
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());

        return [
            'items' => $cart->getItems(),
            'grandTotal' => $moneyFormatter->format($cart->getGrandTotal()),
        ];
    }
}
