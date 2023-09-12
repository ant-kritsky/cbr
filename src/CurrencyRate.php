<?php

namespace App;

use DI\Container;

class CurrencyRate
{
    private $container;
    const BASE_CURRENCY = 'RUR';

    function __construct(Container $container)
    {
        $this->container = $container;
    }
}