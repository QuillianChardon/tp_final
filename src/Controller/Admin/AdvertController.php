<?php

namespace App\Controller\Admin;

use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/adverts', name: 'advert_')]
class AdvertController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AdvertRepository $advertRepository): Response
    {
        $adverts = $advertRepository->findAll();
        return $this->render('admin/advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }
}
