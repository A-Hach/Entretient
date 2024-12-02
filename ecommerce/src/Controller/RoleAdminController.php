<?php

namespace App\Controller;

use App\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class RoleAdminController extends AbstractController
{
    /**
     * @Route("/roles", name="admin_roles_index")
     */
    public function index()
    {
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        return $this->render('admin/roles/index.html.twig', ['roles' => $roles]);
    }

    /**
     * @Route("/roles/{id}", name="admin_roles_show")
     */
    public function show(Role $role)
    {
        return $this->render('admin/roles/show.html.twig', ['role' => $role]);
    }

    /**
     * @Route("/roles/new", name="admin_roles_new")
     */
    public function new(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($role);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_roles_index');
        }

        return $this->render('admin/roles/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/roles/{id}/edit", name="admin_roles_edit")
     */
    public function edit(Request $request, Role $role)
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_roles_index');
        }

        return $this->render('admin/roles/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/roles/{id}/delete", name="admin_roles_delete")
     */
    public function delete(Request $request, Role $role)
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $this->getDoctrine()->getManager()->remove($role);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('admin_roles_index');
    }
}
