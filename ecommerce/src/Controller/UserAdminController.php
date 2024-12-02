<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\Users;
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
class UserAdminController extends AbstractController
{
    #[Route('/users', name: 'app_users_admin', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(Users::class)->findAll();
        return $this->render('user_admin/index.html.twig', ['users' => $users]);
    }

    
    #[Route('/users/{id}', name: 'app_users_admin_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager)
    {
        $utilisateur = $entityManager->getRepository(Users::class)->find($id);
        return $this->render('user_admin/show.html.twig', ['utilisateur' => $utilisateur]);
    }

    
    #[Route('/new/users', name: 'app_users_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $roles = $entityManager->getRepository(Roles::class)->findAll();
        if($request->isMethod('POST')){
        $utilisateur = new Users();
        $utilisateur->setEmail($request->request->get('email'));
        $utilisateur->setName($request->request->get('name'));
        $utilisateur->setPassword($request->request->get('password'));
        $utilisateur->setRole($entityManager->getRepository(Roles::class)->find($request->request->get('role')));
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('app_users_admin');
        }

        return $this->render('user_admin/new.html.twig', ['roles' => $roles]);
    }

    
    #[Route('/users/{id}/edit', name: 'app_users_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,int $id, EntityManagerInterface $entityManager)
    {
        $utilisateur = $entityManager->getRepository(Users::class)->find($id);
        $roles = $entityManager->getRepository(Roles::class)->findAll();
        if($request->isMethod('POST')){
            $utilisateur->setEmail($request->request->get('email'));
            $utilisateur->setName($request->request->get('name'));
            if($request->request->get('password') != ""){
                $utilisateur->setPassword($request->request->get('password'));
            }
            $utilisateur->setRole($entityManager->getRepository(Roles::class)->find($request->request->get('role')));
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            return $this->redirectToRoute('app_users_admin');
        }
        

        return $this->render('user_admin/edit.html.twig', ['user' => $utilisateur, 'roles' => $roles]); 
    }
    
    #[Route('/users/{id}/delete', name: 'app_users_admin_delete', methods: ['GET'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager)
    {
        $utilisateur = $entityManager->getRepository(Users::class)->find($id);
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('app_users_admin');
    }
}

