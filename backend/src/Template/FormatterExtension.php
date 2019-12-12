<?php

declare(strict_types=1);

namespace App\Template;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormatterExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);

        return '$' . $price;
    }
}
