<?php

namespace App\DataFixtures;

use App\Entity\AdminUser;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {

        $userAdmin = new AdminUser();

        $userAdmin->setEmail('test@test.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->encoder->hashPassword($userAdmin, 'password'));

        $manager->persist($userAdmin);
        $manager->flush();
    }
}
