<?php

namespace App\Consumer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;

/**
 * Class PunkApi
 * @package App\Consumer
 */
class PunkApi
{
    const API_ENDPOINT = 'https://api.punkapi.com/v2/beers';
    //https://api.punkapi.com/v2/beers?food=chocolate&ibu_lt=30 trae solo lo de chocolate path 1
    //https://api.punkapi.com/v2/beers?food=chocolate&page=2&per_page=10  con paginado
    //https://api.punkapi.com/v2/beers?food=chocolate&ids=96 get by id

    /**
     * @param $id
     * @return array|mixed
     * @throws GuzzleException
     */
    public static function getBeerById($id)
    {
        $client = new Client();

        try {
            $resp = $client->request('GET', self::API_ENDPOINT, ['query' => ['ids' => $id]]);
            $data = json_decode($resp->getBody()->getContents());

        } catch (ClientException $e) {
            $data = [];
        }

        return $data;
    }

    /**
     * @param string $food
     * @param int $page
     * @param int $per_page
     * @return array|mixed
     * @throws GuzzleException
     */
    public static function getBeerByFood(string $food, $page = 1, $per_page = 25)
    {
        $client = new Client();

        try {
            $resp = $client->request('GET', self::API_ENDPOINT,
                [
                    'query' => ['food' => $food, 'page' => $page, 'per_page' => $per_page]
                ]);
            $data = json_decode($resp->getBody()->getContents());

        } catch (ClientException $e) {
            $data = [];
        }

        return $data;
    }

}
