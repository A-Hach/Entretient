<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneDeCommande;
use App\Entity\OrderItems;
use App\Entity\Orders;
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
class CommandeAdminController extends AbstractController
{
    
    #[Route('/admin/commandes', name: 'app_commandes_admin', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager)
    {
        $commandes = $entityManager->getRepository(Orders::class)->findAll();
        return $this->render('commande_admin/index.html.twig', ['commandes' => $commandes]);
    }

    
    #[Route('/admin/commandes/{id}', name: 'app_commandes_admin_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $commande = $entityManager->getRepository(Orders::class)->find($id);
        $commandeItems = $entityManager->getRepository(OrderItems::class)->findBy(['Orders' => $id]);
        return $this->render('commande_admin/show.html.twig', ['commande' => $commande, 'commandeItems' => $commandeItems]);
    }

    

    

    
    #[Route('/admin/commandes/{id}/delete', name: 'app_commandes_admin_delete', methods: ['GET'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager)
    {
        $commande = $entityManager->getRepository(Orders::class)->find($id);
        $entityManager->remove($commande);
        $entityManager->flush();

        return $this->redirectToRoute('app_commandes_admin');
    }
}