<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class AuthController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
    
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_product');
        }
        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $user = $entityManager->getRepository(Users::class)->findOneBy(['Email' => $email]);
            dd($this->hasher->isPasswordValid($user, $password));
            if (!$user || !$this->hasher->isPasswordValid($user, $password)) {
                $this->addFlash('error', 'Bad credentials');

                return $this->redirectToRoute('login');
            }
            $token = bin2hex(random_bytes(length: 32));
            $request->getSession()->set('token', $token);
            $this->getUser();

            return $this->redirectToRoute('app_product');
        }
        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->invalidate();

        return $this->redirectToRoute('login');
    }

    #[Route('/register', name: 'app_register')]
    public function register(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_product');
        }
        if(request()->isMethod('POST')){
            $user = new Users();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $request->request->get('password')
            )
        );
        $user->setRole($entityManager->getRepository(Roles::class)->findOneBy(['name' => 'ROLE_USER']));

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Registration successful!');

        return $this->redirectToRoute('login');
        }
        
        return $this->render('auth/register.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
}

