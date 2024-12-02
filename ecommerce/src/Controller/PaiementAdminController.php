<?php

namespace App\Controller;

use App\Entity\Paiement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class PaiementAdminController extends AbstractController
{
    /**
     * @Route("/paiements", name="admin_paiements_index")
     */
    public function index()
    {
        $paiements = $this->getDoctrine()->getRepository(Paiement::class)->findAll();

        return $this->render('admin/paiements/index.html.twig', ['paiements' => $paiements]);
    }

    /**
     * @Route("/paiements/{id}", name="admin_paiements_show")
     */
    public function show(Paiement $paiement)
    {
        return $this->render('admin/paiements/show.html.twig', ['paiement' => $paiement]);
    }

    /**
     * @Route("/paiements/new", name="admin_paiements_new")
     */
    public function new(Request $request)
    {
        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($paiement);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_paiements_index');
        }

        return $this->render('admin/paiements/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/paiements/{id}/edit", name="admin_paiements_edit")
     */
    public function edit(Request $request, Paiement $paiement)
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_paiements_index');
        }

        return $this->render('admin/paiements/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/paiements/{id}/delete", name="admin_paiements_delete")
     */
    public function delete(Request $request, Paiement $paiement)
    {
        if ($this->isCsrfTokenValid('delete' . $paiement->getId(), $request->request->get('_token'))) {
            $this->getDoctrine()->getManager()->remove($paiement);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('admin_paiements_index');
    }
}
