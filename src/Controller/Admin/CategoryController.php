<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, PaginatorInterface $paginatorInterface, CategoryRepository $categoryRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $query = $categoryRepository->createQueryBuilder('categories');
        $categories = $paginatorInterface->paginate($query, $page, 30);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, new Category());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('category_index');
        }


        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('category_index');
        }


        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        if (count($category->getAdverts()) > 0) {
            $this->addFlash('danger', "Il y a " . count($category->getAdverts()) . " produit pour la categorie " . $category->getName());
        } else {
            if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
                $em->remove($category);
                $em->flush();
                $this->addFlash('success', "Suppression effectué");
            }
        }


        return $this->redirectToRoute('category_index');
    }
}
