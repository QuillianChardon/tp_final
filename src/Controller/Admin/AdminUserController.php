<?php

namespace App\Controller\Admin;

use App\Entity\AdminUser;
use App\Form\AdminUserType;
use App\Repository\AdminUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    #[Route('/', name: 'admin_user_index', methods: ['GET'])]
    public function index(AdminUserRepository $adminUserRepository): Response
    {

        return $this->render('admin/admin_user/index.html.twig', [
            'admin_users' => $adminUserRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/new', name: 'admin_user_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adminUser = new AdminUser();
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            // $adminUser->setPassword($encoder->hashPassword($adminUser, $adminUser->getPlainPassword()));
            // $adminUser->setPlainPassword(null);

            $entityManager->persist($adminUser);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_user/add.html.twig', [
            'admin_user' => $adminUser,
            'form' => $form,
        ]);
    }



    #[Route('/edit/{id}/', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdminUser $adminUser, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $adminUser->setPassword($encoder->hashPassword($adminUser, $adminUser->getPlainPassword()));
            // $adminUser->setPlainPassword(null);

            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_user/edit.html.twig', [
            'admin_user' => $adminUser,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, AdminUser $adminUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() != $adminUser) {
            if ($this->isCsrfTokenValid('delete' . $adminUser->getId(), $request->request->get('_token'))) {
                $entityManager->remove($adminUser);
                $entityManager->flush();
                $this->addFlash('success', "Suppression effectué");
            }
        } else {
            $this->addFlash('danger', 'On ne peut pas s\'auto supprimer');
        }
        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
