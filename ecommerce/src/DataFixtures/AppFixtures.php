<?php
namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\Products;
use App\Entity\Roles;
use App\Entity\UserRole;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // ...
    public function load(ObjectManager $manager): void
    {
        //Categories
        for ($i = 0; $i < 10; $i++) {
            $category = new Categories();
            $category->setName('category ' . $i);
            $manager->persist($category);
        }
        //Products
        for ($i = 0; $i < 20; $i++) {
            $product = new Products();
            $product->setName('product ' . $i);
            $product->setDescription('description ' . $i);
            $product->setCategorie($category);
            $product->setPrice(mt_rand(10, 100));
            $manager->persist($product);
        }
        //Roles
        $roleAdmin = new Roles();
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        $roleUser = new Roles();
        $roleUser->setName('ROLE_USER');
        $manager->persist($roleUser);
        $manager->flush();
        //Users
        //Admin
        $user = new Users();
        $user->setName('admin');
        $user->setEmail('admin@example.com');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $user->setRole($roleAdmin);
        $manager->persist($user);
        //User
        $user = new Users();
        $user->setName('user1');
        $user->setEmail('user1@example.com');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $user->setRole($roleUser);
        $manager->persist($user);
        $user = new Users();
        $user->setName('user2');
        $user->setEmail('user2@example.com');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $user->setRole($roleUser);
        $manager->persist($user);
        //Orders
        for ($i = 0; $i < 10; $i++) {
            $order = new Orders();
            $order->setDate(new \DateTimeImmutable());
            $order->setTotal(mt_rand(10, 100));
            $order->setUser($user);
            $manager->persist($order);
        }
        $manager->flush();
        //OrdersItems

        $orderItem = new OrderItems();
        $orderItem->setQuantity(mt_rand(1, 10));
        $orderItem->setUnitPrice($product->getPrice());
        $orderItem->setProduct($product);
        $orderItem->setOrders($order);
        $manager->persist($orderItem);

        //Payments

        $payment = new Payment();
        $payment->setDate(new \DateTimeImmutable());
        $payment->setStripeId($order->getId());
        $payment->setTotal($order->getTotal());
        $payment->setOrders($order);
        $manager->persist($payment);

        $manager->flush();
    }
}
