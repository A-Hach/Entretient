<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\OrderItems;
use App\Entity\Orders;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Products;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Stripe;
class CartController extends AbstractController
{

    #[Route('/cart', name: 'cart', methods: ['GET'])]
    public function show(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $cart = $entityManager->getRepository(Cart::class)->findBy(['User' => $entityManager->getRepository(Users::class)->find($session->get('userId'))]);
        return $this->render('cart/show.html.twig', ['cart' => $cart]);
    }


    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(int $id, EntityManagerInterface $entityManager, Request $request, SessionInterface $session): Response
    {
        $cart = new Cart();
        $cart->setProduct($entityManager->getRepository(Products::class)->find($id));
        $user = $entityManager->getRepository(Users::class)
            ->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        $session->set('userId', $user->getId());
        $cart->setUser($user);
        $cart->setQuantity($request->request->get('quantity', 1));
        $entityManager->persist($cart);
        $entityManager->flush();
        return $this->redirectToRoute('cart');
    }


    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    public function remove(int $id, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $cart = $entityManager->getRepository(Cart::class)->findOneBy(['User' => $entityManager->getRepository(Users::class)->find($session->get('userId')), 'Product' => $entityManager->getRepository(Products::class)->find($id)]);
        $entityManager->remove($cart);
        $entityManager->flush();
        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/modify/{id}', name: 'cart_modify', methods: ['POST'])]
    public function modify(int $id, EntityManagerInterface $entityManager, Request $request, SessionInterface $session): Response
    {
        $quantity = $request->request->get('quantity', 1);
        $cart = $entityManager->getRepository(Cart::class)->findOneBy(['User' => $entityManager->getRepository(Users::class)->find($session->get('userId')), 'Product' => $entityManager->getRepository(Products::class)->find($id)]);
        $cart->setQuantity($quantity);
        $entityManager->persist($cart);
        $entityManager->flush();
        return $this->redirectToRoute('cart');
    }

    #[Route('/checkout', name: 'checkout', methods: ['GET', 'POST'])]
    public function checkout(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $cart = $entityManager->getRepository(Cart::class)->findBy(['User' => $entityManager->getRepository(Users::class)->find($session->get('userId'))]);
        if (empty($cart)) {
            return $this->redirectToRoute('cart');
        }
        if ($request->isMethod('GET')) {
            return $this->render('checkout/index.html.twig', ['cart' => $cart]);
        }
        if ($request->isMethod('POST')) {

            Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
            Stripe\Charge::create([
                'amount' => $request->request->get('total') * 100,
                'currency' => 'eur',
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
            ]);
            $order = new Orders();
            $order->setUser($entityManager->getRepository(Users::class)->find($session->get('userId')));
            $order->setDate(new \DateTime());
            $total = 0;
            foreach ($cart as $item) {
                $total += $item->getProduct()->getPrice() * $item->getQuantity();
                $orderItem = new OrderItems();
                $orderItem->setProduct($item->getProduct());
                $orderItem->setQuantity($item->getQuantity());
                $orderItem->setOrders($order);
                $orderItem->setUnitPrice($item->getProduct()->getPrice());
                $entityManager->persist($orderItem);
            }
            $order->setTotal($total);
            $entityManager->persist($order);
            $entityManager->flush();
            foreach ($cart as $cartItem) {
                $entityManager->remove($cartItem);
                $entityManager->flush();
            }
            return $this->redirectToRoute('order_success');
        }
    }
    #[Route('/checkout/success', name: 'order_success', methods: ['GET'])]
    public function orderSuccess(): Response
    {
        return $this->render('checkout/success.html.twig');
    }
}

