<?php

namespace App\Tests;

use App\Repository\AdminUserRepository;
use App\Repository\CategoryRepository;
use App\Entity\Advert;
use App\Repository\AdvertRepository;
use SebastianBergmann\Environment\Console;
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

    // public function testAdvertUnpublish(): void
    // {
    //     $client = $this->connection();

    //     /** @var AdvertRepository */
    //     $advertRepository = static::getContainer()->get(AdvertRepository::class);
    //     $advertDraft = null;
    //     foreach ($advertRepository->findall() as $advert) {
    //         if ($advert->getState() == 'published') {
    //             $advertDraft = $advert;
    //             break;
    //         }
    //     }

    //     if ($advertDraft !== null) {
    //         $client->request('GET', '/admin/adverts/' . $advertDraft->getId() . '/unpublish');
    //         $this->assertEquals('rejected', $advertDraft->getState(), 'Correspondence');
    //     }
    // }


    // public function testUserDelete(): void
    // {
    //     $client = $this->connection();
    //     /** @var AdminUserRepository */
    //     $adminUserRepository = static::getContainer()->get(AdminUserRepository::class);
    //     $allUser = $adminUserRepository->findAll();
    //     $userTest = null;
    //     foreach ($allUser as $value) {
    //         if ($value->getEmail() !== 'test@test.fr' && $userTest == null) {
    //             $userTest = $value;
    //         }
    //     }

    //     $client->request('POST', '/admin/user/delete/' . $userTest->getId());
    //     $client->followRedirect();

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'AdminUser index');
    // }

    // public function testUserModif(): void
    // {
    //     $client = $this->connection();

    //     /** @var AdminUserRepository */
    //     $adminUserRepository = static::getContainer()->get(AdminUserRepository::class);
    //     $allUser = $adminUserRepository->findAll();

    //     $userTest = null;
    //     foreach ($allUser as $value) {
    //         if ($value->getEmail() !== 'test@test.fr' && $userTest == null) {
    //             $userTest = $value;
    //         }
    //     }


    //     $crawler = $client->request('GET', '/admin/user/');
    //     $link = $crawler->filter('a.btn-warning[href*="edit/' . $userTest->getId() . '/"]')->link();

    //     $client->click($link);
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h2', 'Modification new AdminUser');

    //     $newEmail = 'azertyNew5@gmail.com';
    //     $client->submitForm("Modifier la l'utilisateur", [
    //         'admin_user[email]' => $newEmail
    //     ]);
    //     $crawler = $client->followRedirect();
    //     $User = $adminUserRepository->findOneById($userTest->getId());
    //     $this->assertEquals($newEmail, $User->getEmail(), 'même email okay');
    // }


    public function testUserAdd(): void
    {
        $client = $this->connection();

        /** @var AdminUserRepository */
        $adminUserRepository = static::getContainer()->get(AdminUserRepository::class);

        $crawler = $client->request('GET', '/admin/user/');
        $link = $crawler->filter('a[href*="/user/new"]')->link();

        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Create new AdminUser');
        $newName = 'azertyCreate';
        $newEmail = 'azertyCreate@test.com';
        $newPwd = 'azerty';
        $client->submitForm('Créer la l\'utilisateur', [
            'admin_user[username]' => $newName,
            'admin_user[email]' => $newEmail,
            'admin_user[plainpassword]' => $newPwd,
        ]);
        $crawler = $client->followRedirect();
        $newUser = $adminUserRepository->findOneByEmail($newEmail);
        $this->assertNotNull($newUser, 'même nom okay');
    }
}
