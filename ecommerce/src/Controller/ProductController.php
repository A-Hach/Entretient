<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
class ProductController extends AbstractController
{
    
    #[Route('/', name: 'app_product', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // if(!$this->getUser()){
        //     return $this->redirectToRoute('app_login');
        // }
        $products = $entityManager->getRepository(Products::class)->findAll();
        return $this->render('product/list.html.twig', ['products' => $products]);
    }

    #[Route('/product/{id}', name: 'app_product_detail', methods: ['GET'])]
    public function detail(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);
        return $this->render('product/detail.html.twig', ['product' => $product]);
    }
}
