<?php

namespace App\Controller\Admin;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/admin/adverts', name: 'advert_')]
class AdvertController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AdvertRepository $advertRepository): Response
    {
        $adverts = $advertRepository->findBy(['state' => 'published']);
        return $this->render('admin/advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }

    #[Route('/all/state', name: 'all_state')]
    public function allState(AdvertRepository $advertRepository): Response
    {
        $adverts = $advertRepository->findAll();
        return $this->render('admin/advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }

    // Mise en commentaire des création / modification / delete des adverts
    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdvertType::class, new Advert());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('advert_index');
        }

        return $this->render('admin/advert/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Advert $advert, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('advert_index');
        }


        return $this->render('admin/advert/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, Advert $advert, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $advert->getId(), $request->request->get('_token'))) {
            $em->remove($advert);
            $em->flush();
        }

        return $this->redirectToRoute('advert_index');
    }
    // */

    #[Route(path: '/{id}/{to}', name: 'transition', methods: ['GET'])]
    public function applyTransition(WorkflowInterface $advertStateMachine, EntityManagerInterface $em, Advert $advert, string $to): Response
    {
        $advertStateMachine->apply($advert, $to);
        $em->flush();
        return $this->redirectToRoute('advert_index');
    }
}
