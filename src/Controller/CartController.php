<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartData = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = $productRepository->find($item['product_id']);

            if ($product) {
                $cartData[] = [
                    'product' => $product,
                    'size' => $item['size'],
                ];

                $total += $product->getPrice();
            }
        }

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartData,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Product $product, Request $request, SessionInterface $session): Response
    {
        $size = $request->request->get('size');
        $cart = $session->get('cart', []);

        $cart[] = [
            'product_id' => $product->getId(),
            'size' => $size,
        ];

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{index}', name: 'app_cart_remove')]
    public function remove(int $index, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
        }

        $session->set('cart', array_values($cart));

        return $this->redirectToRoute('app_cart');
    }
}