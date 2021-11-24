<?php

namespace App\Controller\Admin;

use App\Repository\AdminUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'user_')]
class Admin extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AdminUserRepository $adminUserRepository): Response
    {
        $users = $adminUserRepository->findAll();
        return $this->render('admin/advert/index.html.twig', [
            'users' => $users,
        ]);
    }
}
