<?php

namespace App\Controller;

use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adverts', name: 'advert_')]
class AdvertController extends AbstractController
{
    #[Route('/', name: 'view')]
    public function index(AdvertRepository $advertRepository): Response
    {
        $adverts = $advertRepository->findAll();
        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }
}
