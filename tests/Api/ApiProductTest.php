<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiProductTest extends WebTestCase
{
    public function testGetAllProducts(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetSingleProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/products/1');

        if ($client->getResponse()->getStatusCode() === 200) {
            $this->assertJson($client->getResponse()->getContent());
            $this->assertStringContainsString('"title"', $client->getResponse()->getContent());
        } else {
            $this->assertResponseStatusCodeSame(404);
        }
    }
}