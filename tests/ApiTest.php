<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ApiTest extends ApiTestCase
{

    public function getCategory()
    {
        $response = static::createClient()->request('GET', '/categories');
        $categories = $response->toArray()['hydra:member'];
        $category = $categories[0];
        return $category;
    }


    // public function testGetCategory(): void
    // {
    //     $response = static::createClient()->request('GET', '/categories');
    //     $this->assertResponseIsSuccessful();
    //     $this->assertJsonContains(["@id" => '/categories']);
    // }

    // public function testGetOneCategory(): void
    // {
    //     $response = static::createClient()->request('GET', '/categories');
    //     $categories = $response->toArray()['hydra:member'];
    //     $category = $categories[0];

    //     $response = static::createClient()->request('GET', $category['@id']);

    //     dump($response->toArray());
    //     $this->assertResponseIsSuccessful();
    //     $this->assertJsonContains(['@id' => $category['@id']]);
    // }

    // public function testGetAdvert(): void
    // {
    //     $response = static::createClient()->request('GET', '/adverts');
    //     $this->assertResponseIsSuccessful();
    //     $this->assertJsonContains(["@id" => '/adverts']);
    // }

    // public function testGetOneAdvert(): void
    // {
    //     $response = static::createClient()->request('GET', '/adverts');
    //     $adverts = $response->toArray()['hydra:member'];
    //     $advert = $adverts[0];

    //     $response = static::createClient()->request('GET', $advert['@id']);

    //     dump($response->toArray());
    //     $this->assertResponseIsSuccessful();
    //     $this->assertJsonContains(['@id' => $advert['@id']]);
    // }


    public function testAddAdvert(): void
    {
        $response = static::createClient()->request('GET', '/adverts');
        $nbItem = $response->toArray()['hydra:totalItems'];
        $category = $this->getCategory();
        $response = static::createClient()->request('POST', '/adverts', ['json' => [
            'title' => 'testApi',
            'content' => 'Test depuis l\'api',
            'author' => 'MoiMeme',
            'email' => 'test@test.fr',
            'category' => $category['@id'],
            'price' => 15,
        ]]);

        $response = static::createClient()->request('GET', $response->toArray()['@id']);
        $this->assertResponseIsSuccessful();
        $response = static::createClient()->request('GET', '/adverts');
        $this->assertEquals($nbItem + 1, $response->toArray()['hydra:totalItems'], 'valeur identique');
    }
}
