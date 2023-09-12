<?php

namespace App;

use DI\Container;

class CbrCurrencyRateFetcher {
    const API_ENDPOINT = 'http://www.cbr.ru/scripts/XML_daily.asp';
    private $container;

    function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Получение курсов валют
     *
     * @param string $date Дата в формате "dd.mm.yyyy". Если не указана, используется текущая дата.
     * @return array Курсы валют
     */
    public function getRates($date = null) {
        $url = self::API_ENDPOINT;

        if (!empty($date)) { // TODO: pregmatch date
            $url .= '?date_req=' . $date;
        }

        $xmlContent = file_get_contents($url);
        if (!$xmlContent) {
            throw new \Exception("Ошибка при получении данных с cbr.ru");
        }

        $xml = simplexml_load_string($xmlContent);

        $rates = [];
        foreach ($xml->Valute as $valute) {
            var_dump($valute->CharCode);
            $rates[(string) $valute->CharCode] = [
                'name' => (string) $valute->Name,
                'value' => (float) str_replace(',', '.', $valute->Value),
                'nominal' => (int) $valute->Nominal
            ];
        }

        return $rates;
    }
}