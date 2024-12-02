<?php

namespace App\Controller;

use App\Entity\LigneDeCommande;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class CommandeItemsAdminController extends AbstractController
{
    /**
     * @Route("/lignes-de-commande", name="admin_lignes_de_commande_index")
     */
    public function index()
    {
        $lignesDeCommande = $this->getDoctrine()->getRepository(LigneDeCommande::class)->findAll();
        return $this->render('admin/lignes_de_commande/index.html.twig', ['lignesDeCommande' => $lignesDeCommande]);
    }
    /**
     * @Route("/lignes-de-commande/{id}", name="admin_lignes_de_commande_show")
     */
    public function show(LigneDeCommande $ligneDeCommande)
    {
        return $this->render('admin/lignes_de_commande/show.html.twig', ['ligneDeCommande' => $ligneDeCommande]);
    }

    /**
     * @Route("/lignes-de-commande/new", name="admin_lignes_de_commande_new")
     */
    public function new(Request $request)
    {
        $ligneDeCommande = new LigneDeCommande();
        $form = $this->createForm(LigneDeCommandeType::class, $ligneDeCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($ligneDeCommande);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_lignes_de_commande_index');
        }

        return $this->render('admin/lignes_de_commande/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/lignes-de-commande/{id}/edit", name="admin_lignes_de_commande_edit")
     */
    public function edit(Request $request, LigneDeCommande $ligneDeCommande)
    {
        $form = $this->createForm(LigneDeCommandeType::class, $ligneDeCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_lignes_de_commande_index');
        }

        return $this->render('admin/lignes_de_commande/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/lignes-de-commande/{id}/delete", name="admin_lignes_de_commande_delete")
     */
    public function delete(Request $request, LigneDeCommande $ligneDeCommande)
    {
        if ($this->isCsrfTokenValid('delete'.$ligneDeCommande->getId(), $request->request->get('_token'))) {
            $this->getDoctrine()->getManager()->remove($ligneDeCommande);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('admin_lignes_de_commande_index');
    }

}
