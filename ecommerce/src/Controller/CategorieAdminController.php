<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class CategorieAdminController extends AbstractController
{

    #[Route('/admin/categories', name: 'app_categories_admin', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager)
    {
        $categories = $entityManager->getRepository(Categories::class)->findAll();
        return $this->render('categorie_admin/index.html.twig', ['categories' => $categories]);
    }


    #[Route('/admin/categories/{id}', name: 'app_categories_admin_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        return $this->render('categorie_admin/show.html.twig', ['categorie' => $categorie]);
    }


    #[Route('/admin/new/categories', name: 'app_categories_admin_new', methods: ['POST', 'GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        if ($request->isMethod('POST')) {
            $categorie = new Categories();
            $categorie->setName($request->request->get('name'));

            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories_admin');
        }
        return $this->render('categorie_admin/newcategorie.html.twig');
    }


    #[Route('/admin/categories/{id}/edit', name: 'app_categories_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager)
    {
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        if ($request->isMethod('POST')) {
            $categorie->setName($request->request->get('name'));

            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories_admin');
        }

        return $this->render('categorie_admin/edit.html.twig', ['categorie' => $categorie]);
    }


    #[Route('/admin/categories/{id}/delete', name: 'app_categories_admin_delete', methods: ['GET'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager)
    {
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('app_categories_admin');
    }
}
