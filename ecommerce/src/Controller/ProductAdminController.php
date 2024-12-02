<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Categories;
use App\Entity\Commande;
use App\Entity\LigneDeCommande;
use App\Entity\Utilisateur;
use App\Entity\Role;
use App\Entity\Paiement;
use App\Entity\Products;
use Doctrine\ORM\EntityManager;
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
class ProductAdminController extends AbstractController
{

    #[Route('/admin/produits', name: 'app_produits_admin', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager)
    {
        $produits = $entityManager->getRepository(Products::class)->findAll();
        return $this->render('product_admin/index.html.twig', ['produits' => $produits]);
    }


    #[Route('/admin/produits/{id}', name: 'app_produits_admin_show', methods: ['GET'])]
    public function show(Products $produit)
    {
        return $this->render('product_admin/show.html.twig', ['produit' => $produit]);
    }


    #[Route('/admin/new/produits', name: 'app_produits_admin_new', methods: ['POST', 'GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $categorie = $entityManager->getRepository(Categories::class)->findAll();
        if ($request->isMethod('POST')) {
            $produit = new Products();
            $produit->setName($request->request->get('name'));
            $produit->setDescription($request->request->get('description'));
            $produit->setPrice($request->request->get('price'));
            $produit->setCategorie($entityManager->getRepository(Categories::class)->find($request->request->get('categorie')));

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_admin');
        }

        return $this->render('product_admin/newproduit.html.twig', ['categories' => $categorie]);
    }


    #[Route('/admin/produits/{id}/edit', name: 'app_produits_admin_edit', methods: ['POST', 'GET'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager)
    {
        $produit = $entityManager->getRepository(Products::class)->find($id);
        $categorie = $entityManager->getRepository(Categories::class)->findAll();
        if ($request->isMethod('POST')) {

            $produit->setName($request->request->get('name'));
            $produit->setDescription($request->request->get('description'));
            $produit->setPrice($request->request->get('price'));
            $produit->setCategorie($entityManager->getRepository(Categories::class)->find($request->request->get('categorie')));

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_admin');
        }
        return $this->render('product_admin/edit.html.twig', ['produit' => $produit, 'categories' => $categorie]);
    }

    /**
     * @Route("/produits/{id}/delete", name="admin_produits_delete")
     */
    #[Route('/admin/produits/{id}/delete', name: 'app_produits_admin_delete', methods: ['GET'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager)
    {
        $produit = $entityManager->getRepository(Products::class)->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_produits_admin');
    }
}
