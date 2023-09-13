<?php

namespace App;

use \GuzzleHttp\Client;

class CbrCurrencyRateFetcher
{
    const STATUS_OK = 200;
    const API_ENDPOINT = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * Получение курсов валют
     *
     * @param string $date Дата в формате "dd.mm.yyyy".
     * @return array Курсы валют
     */
    static public function getRates($date = null)
    {
        $url = self::API_ENDPOINT;

        if (!empty($date)) {
            $date = date("d/m/Y", strtotime($date));
            $url .= '?date_req=' . $date;
        }

        $client = new Client();
        $response = $client->request('GET', $url);
        $responseStatus = $response->getStatusCode();

        if ($responseStatus != self::STATUS_OK) {
            echo "Ошибка при получении данных с cbr.ru. Статус: " . $responseStatus;
            exit;
        }

        $xml = simplexml_load_string($response->getBody());

        $rates = [];
        foreach ($xml->Valute as $valute) {
            $rates[(string)$valute->CharCode] = [
                'name' => (string)$valute->Name,
                'value' => (float)str_replace(',', '.', $valute->Value),
                'nominal' => (int)$valute->Nominal
            ];
        }

        return $rates;
    }
}