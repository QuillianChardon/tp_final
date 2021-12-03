<?php

namespace App\Tests;

use App\Repository\AdminUserRepository;
use App\Repository\CategoryRepository;
use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfToken;

class InterfaceTest extends WebTestCase
{
    // public function testSomething(): void
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/admin/login');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'Please sign in');
    // }

    public function connection()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(AdminUserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@test.fr');
        $client->loginUser($testUser, 'admin');
        return $client;
    }

    // public function testConnexion(): void
    // {
    //     $client = $this->connection();

    //     $client->request('GET', '/admin/category/');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'Categories');
    // }

    // public function testModifCategory(): void
    // {
    //     $client = $this->connection();

    //     /** @var CategoryRepository */
    //     $categoryRepository = static::getContainer()->get(CategoryRepository::class);
    //     $firstCategory = $categoryRepository->findOneBy([]);


    //     $crawler = $client->request('GET', '/admin/category/');
    //     $link = $crawler->filter('a.btn-warning')->link();


    //     $client->click($link);
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h2', 'modification de la categorie');

    //     $newName = 'azertyNew';
    //     $client->submitForm('Modifier la categorie', [
    //         'category[name]' => $newName
    //     ]);
    //     $crawler = $client->followRedirect();
    //     $this->assertEquals($newName, $firstCategory->getName(), 'même nom okay');
    // }
    // public function testAddCategory(): void
    // {
    //     $client = $this->connection();

    //     /** @var CategoryRepository */
    //     $categoryRepository = static::getContainer()->get(CategoryRepository::class);



    //     $crawler = $client->request('GET', '/admin/category/');
    //     $link = $crawler->filter('a.btn-primary')->link();


    //     $client->click($link);
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h2', 'Ajout d\'une categorie');

    //     $newName = 'azertyCreate';
    //     $client->submitForm('Créer la categorie', [
    //         'category[name]' => $newName
    //     ]);
    //     $crawler = $client->followRedirect();
    //     $firstCategory = $categoryRepository->findOneByName($newName);
    //     $this->assertNotNull($firstCategory, 'même nom okay');
    // }


    // public function testDeleteCategory(): void
    // {
    //     $client = $this->connection();

    //     /** @var CategoryRepository */
    //     $categoryRepository = static::getContainer()->get(CategoryRepository::class);
    //     $lastategory = $categoryRepository->findOneBy([], ['id' => 'desc']);



    //     $client->request('POST', '/admin/category/delete/' . $lastategory->getId());
    //     $client->followRedirect();


    //     $this->assertResponseIsSuccessful('Objet supprimer');
    // }


    // public function testAdvert(): void
    // {
    //     $client = $this->connection();

    //     $client->request('GET', '/admin/adverts/');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'Adverts');
    // }

    // public function testAdvertShow(): void
    // {
    //     $client = $this->connection();

    //     /** @var AdvertRepository */
    //     $advertRepository = static::getContainer()->get(AdvertRepository::class);
    //     $firstAdvert = $advertRepository->findOneBy([]);


    //     $crawler = $client->request('GET', '/admin/adverts/');
    //     $link = $crawler->filter('a.btn-success')->link();


    //     $client->click($link);
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', $firstAdvert->getTitle());
    // }


    // public function testAdvertPublish(): void
    // {
    //     $client = $this->connection();

    //     /** @var AdvertRepository */
    //     $advertRepository = static::getContainer()->get(AdvertRepository::class);
    //     $advertDraft = null;
    //     foreach ($advertRepository->findall() as $advert) {
    //         if ($advert->getState() == 'draft') {
    //             $advertDraft = $advert;
    //             break;
    //         }
    //     }

    //     $crawler = $client->request('GET', '/admin/adverts/' . $advertDraft->getId() . '/publish');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertEquals('published', $advertDraft->getState(), 'Correspondence');
    // }



    // public function testAdvertRejected(): void
    // {
    //     $client = $this->connection();

    //     /** @var AdvertRepository */
    //     $advertRepository = static::getContainer()->get(AdvertRepository::class);
    //     $advertDraft = null;
    //     foreach ($advertRepository->findall() as $advert) {
    //         if ($advert->getState() == 'draft') {
    //             $advertDraft = $advert;
    //             break;
    //         }
    //     }

    //     $crawler = $client->request('GET', '/admin/adverts/' . $advertDraft->getId() . '/reject');
    //     $client->followRedirect();
    //     $this->assertResponseIsSuccessful();
    //     $this->assertEquals('rejected', $advertDraft->getState(), 'Correspondence');
    // }

    public function testAdvertUnpublish(): void
    {
        $client = $this->connection();

        /** @var AdvertRepository */
        $advertRepository = static::getContainer()->get(AdvertRepository::class);
        $advertDraft = null;
        foreach ($advertRepository->findall() as $advert) {
            if ($advert->getState() == 'published') {
                $advertDraft = $advert;
                break;
            }
        }

        if ($advertDraft !== null) {
            $client->request('GET', '/admin/adverts/' . $advertDraft->getId() . '/unpublish');
            $this->assertEquals('rejected', $advertDraft->getState(), 'Correspondence');
        }
    }
}
