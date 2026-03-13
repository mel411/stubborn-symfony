<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Stock;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/products')]
final class AdminProductController extends AbstractController
{
    #[Route('', name: 'app_admin_products')]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);

            $this->syncStock($product, 'XS', (int) $form->get('stockXS')->getData(), $entityManager);
            $this->syncStock($product, 'S', (int) $form->get('stockS')->getData(), $entityManager);
            $this->syncStock($product, 'M', (int) $form->get('stockM')->getData(), $entityManager);
            $this->syncStock($product, 'L', (int) $form->get('stockL')->getData(), $entityManager);
            $this->syncStock($product, 'XL', (int) $form->get('stockXL')->getData(), $entityManager);

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin_product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_products_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ProductType::class, $product);

        $form->get('stockXS')->setData($this->getStockQuantity($product, 'XS'));
        $form->get('stockS')->setData($this->getStockQuantity($product, 'S'));
        $form->get('stockM')->setData($this->getStockQuantity($product, 'M'));
        $form->get('stockL')->setData($this->getStockQuantity($product, 'L'));
        $form->get('stockXL')->setData($this->getStockQuantity($product, 'XL'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->syncStock($product, 'XS', (int) $form->get('stockXS')->getData(), $entityManager);
            $this->syncStock($product, 'S', (int) $form->get('stockS')->getData(), $entityManager);
            $this->syncStock($product, 'M', (int) $form->get('stockM')->getData(), $entityManager);
            $this->syncStock($product, 'L', (int) $form->get('stockL')->getData(), $entityManager);
            $this->syncStock($product, 'XL', (int) $form->get('stockXL')->getData(), $entityManager);

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin_product/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier le produit',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_products_delete')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        foreach ($product->getStocks() as $stock) {
            $entityManager->remove($stock);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_products');
    }

    private function getStockQuantity(Product $product, string $size): int
    {
        foreach ($product->getStocks() as $stock) {
            if ($stock->getSize() === $size) {
                return $stock->getQuantity();
            }
        }

        return 0;
    }

    private function syncStock(Product $product, string $size, int $quantity, EntityManagerInterface $entityManager): void
    {
        $existingStock = null;

        foreach ($product->getStocks() as $stock) {
            if ($stock->getSize() === $size) {
                $existingStock = $stock;
                break;
            }
        }

        if ($existingStock) {
            if ($quantity <= 0) {
                $product->removeStock($existingStock);
                $entityManager->remove($existingStock);
            } else {
                $existingStock->setQuantity($quantity);
            }

            return;
        }

        if ($quantity > 0) {
            $stock = new Stock();
            $stock->setSize($size);
            $stock->setQuantity($quantity);
            $stock->setProduct($product);

            $product->addStock($stock);
            $entityManager->persist($stock);
        }
    }
}