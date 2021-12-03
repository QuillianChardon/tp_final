<?php

namespace App\Tests;

use App\Repository\AdminUserRepository;
use App\Repository\CategoryRepository;
use App\Entity\Advert;
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

    public function testConnexion(): void
    {
        $client = $this->connection();

        $client->request('GET', '/admin/category/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Categories');
    }

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


    public function testDeleteCategory(): void
    {
        $client = $this->connection();

        /** @var CategoryRepository */
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $firstCategory = $categoryRepository->findOneBy([]);
        $lastategory = $categoryRepository->findOneBy([], ['id' => 'desc']);

        if (count($firstCategory->getAdverts()) > 0) {

            /** @var Advert $advert */
            foreach ($firstCategory->getAdverts() as $advert) {
                $advert->setCategory($lastategory);
                $firstCategory->removeAdvert($advert);
            }
        }

        $crawler = $client->request('GET', '/admin/category/');


        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $token = $form->get('_token')->getValue();

        dump($token);
        $client->submitForm('Delete', [
            '_token' => $token,
        ]);

        $firstCategoryAfterDelete = $categoryRepository->findOneBy([]);
        dump($firstCategory);
        dump($firstCategoryAfterDelete);
        $this->assertNotEquals($firstCategory, $firstCategoryAfterDelete, 'même nom okay');
    }
}
