<?php

namespace App;

use DI\Container;

class CurrencyRate
{
    private $dateRates;
    const BASE_CURRENCY = 'RUR';

    function __construct(string $dateRates)
    {
        $this->dateRates = unserialize($dateRates);
    }

    public function getRate($currency, $base)
    {
        $baseValue = $base == self::BASE_CURRENCY ? 1 : $this->getRate($base, self::BASE_CURRENCY);

        return $this->dateRates[$currency]['value'] / $baseValue;
    }
}