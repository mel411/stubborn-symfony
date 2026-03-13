<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    #[Route('/cart/checkout', name: 'app_cart_checkout')]
    public function checkout(
        SessionInterface $session,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cart = $session->get('cart', []);

        if (empty($cart)) {
            return $this->redirectToRoute('app_cart');
        }

        $order = new Order();
        $order->setUser($this->getUser());
        $order->setCreatedAt(new \DateTimeImmutable());

        $total = 0;

        foreach ($cart as $item) {
            $product = $productRepository->find($item['product_id']);

            if (!$product) {
                continue;
            }

            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order);
            $orderItem->setProduct($product);
            $orderItem->setSize($item['size']);
            $orderItem->setPrice($product->getPrice());

            $entityManager->persist($orderItem);

            $total += $product->getPrice();
        }

        $order->setTotal($total);

        $entityManager->persist($order);
        $entityManager->flush();

        $session->remove('cart');

        return $this->redirectToRoute('app_order_success', [
            'id' => $order->getId(),
        ]);
    }

    #[Route('/order/success/{id}', name: 'app_order_success')]
    public function success(Order $order): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('checkout/success.html.twig', [
            'order' => $order,
        ]);
    }
}