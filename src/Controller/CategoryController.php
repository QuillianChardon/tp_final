<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorys', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'view')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categorys = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categorys' => $categorys,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, new Category());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('category_view');
        }


        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('category_view');
        }


        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
