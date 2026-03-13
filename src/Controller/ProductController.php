<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findAll();

        $priceRange = $request->query->get('price');

        if ($priceRange) {
            $products = array_filter($products, function (Product $product) use ($priceRange) {
                $price = $product->getPrice();

                return match ($priceRange) {
                    '10-29' => $price >= 10 && $price <= 29,
                    '30-35' => $price >= 30 && $price <= 35,
                    '35-50' => $price > 35 && $price <= 50,
                    default => true,
                };
            });
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'selectedPrice' => $priceRange,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}