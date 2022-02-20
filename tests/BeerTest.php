<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use GuzzleHttp\Client;

/**
 * Class BeerTest
 * @package App\Tests
 */
class BeerTest extends ApiTestCase
{

    public function testGetAllBeerNoPagination(): void
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/beers/');

        $array_respond = json_decode($respond->getBody()->getContents(), true);
        $array_beer = array_pop($array_respond['data']);

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(200, $array_respond['status']);
        $this->assertEquals("success", $array_respond['message']);
        $this->assertFalse(empty($array_respond['data']));
        $this->assertArrayHasKey('id', $array_beer);
        $this->assertArrayHasKey('name', $array_beer);
        $this->assertArrayHasKey('description', $array_beer);
    }

    public function testGetAllBeerWithPagination(): void
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/beers',
            ['query' => ['page' => 1, 'per_page' => 20]]);

        $array_respond = json_decode($respond->getBody()->getContents(), true);
        $array_beer = $array_respond['data'][0];

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(200, $array_respond['status']);
        $this->assertEquals("success", $array_respond['message']);
        $this->assertFalse(empty($array_respond['data']));
        $this->assertCount(20, $array_respond['data']); //verify pagination
        $this->assertArrayHasKey('id', $array_beer);
        $this->assertArrayHasKey('name', $array_beer);
        $this->assertArrayHasKey('description', $array_beer);
    }

    public function testGetAllBeerWithIncorrectPagination(): void
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/beers',
            ['query' => ['page' => -4, 'per_page' => "wtf"]]);

        $array_respond = json_decode($respond->getBody()->getContents(), true);
        $array_page_error = $array_respond['data'][0];
        $array_per_page_error = $array_respond['data'][1];

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(400, $array_respond['status']);
        $this->assertEquals("Invalid query params", $array_respond['message']);

        $this->assertEquals("page", $array_page_error['param']);
        $this->assertEquals(-4, $array_page_error['value']);
        $this->assertEquals("Query", $array_page_error['location']);

        $this->assertEquals("per_page", $array_per_page_error['param']);
        $this->assertEquals("wtf", $array_per_page_error['value']);
        $this->assertEquals("Query", $array_per_page_error['location']);
    }

    public function testGetBeerByIdSuccessParam(): void
    {
//        $client = static::createClient();
//        $respond = $client->request('GET', '/api/beers/96');
//        $this->assertResponseIsSuccessful();

        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/beers/5');            // request beer with ID 5
        $array_respond = json_decode($respond->getBody()->getContents(), true);

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(200, $array_respond['status']);
        $this->assertEquals("success", $array_respond['message']);
        $this->assertEquals(5, $array_respond['data']['id']);           // validates that the request returns

    }

    public function testGetBeerByIdIncorrectParam(): void
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/beers/-5');

        $array_respond = json_decode($respond->getBody()->getContents(), true);

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(400, $array_respond['status']);
        $this->assertEquals("Invalid path params", $array_respond['message']);
        $this->assertEquals("id", $array_respond['data']['param']);
        $this->assertEquals(-5, $array_respond['data']['value']);

    }

    public function testGetBeerByFoodNoPagination(): void
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/food/search/chicken');

        $array_respond = json_decode($respond->getBody()->getContents(), true);
        $array_beer = $array_respond['data'][0];

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(200, $array_respond['status']);
        $this->assertEquals("success", $array_respond['message']);
        $this->assertFalse(empty($array_respond['data']));
        $this->assertArrayHasKey('id', $array_beer);
        $this->assertArrayHasKey('name', $array_beer);
        $this->assertArrayHasKey('description', $array_beer);
    }

    public function testGetBeerByFoodWithPagination(): void
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/food/search/chicken',
            ['query' => ['page' => 1, 'per_page' => 30]]);

        $array_respond = json_decode($respond->getBody()->getContents(), true);
        $array_beer = $array_respond['data'][0];

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(200, $array_respond['status']);
        $this->assertEquals("success", $array_respond['message']);
        $this->assertFalse(empty($array_respond['data']));
        $this->assertCount(30, $array_respond['data']); //verify pagination
        $this->assertArrayHasKey('id', $array_beer);
        $this->assertArrayHasKey('name', $array_beer);
        $this->assertArrayHasKey('description', $array_beer);
    }

    public function testGetBeerByFoodWithIncorrectPagination(): void
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $respond = $client->request('GET', '/api/food/search/chicken',
            ['query' => ['page' => -1, 'per_page' => "asd"]]);

        $array_respond = json_decode($respond->getBody()->getContents(), true);
        $array_page_error = $array_respond['data'][0];
        $array_per_page_error = $array_respond['data'][1];

        $this->assertEquals(200, $respond->getStatusCode());
        $this->assertEquals(400, $array_respond['status']);
        $this->assertEquals("Invalid query params", $array_respond['message']);

        $this->assertEquals("page", $array_page_error['param']);
        $this->assertEquals(-1, $array_page_error['value']);
        $this->assertEquals("Query", $array_page_error['location']);

        $this->assertEquals("per_page", $array_per_page_error['param']);
        $this->assertEquals("asd", $array_per_page_error['value']);
        $this->assertEquals("Query", $array_per_page_error['location']);
    }


}
