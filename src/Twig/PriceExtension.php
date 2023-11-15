<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceExtension extends AbstractExtension
{

    public function __construct()
    {

    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'priceFormat']),
        ];
    }

    public function priceFormat($value)
    {

        return $value > 0 ? number_format($value, 0, ' ', ' ').' руб.' : '';
    }
}