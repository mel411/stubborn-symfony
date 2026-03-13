<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productsData = [
            ['name' => 'Blackbelt',   'price' => 29.90, 'image' => '1.jpeg',   'isFeatured' => true],
            ['name' => 'BlueBelt',    'price' => 29.90, 'image' => '2.jpeg',    'isFeatured' => false],
            ['name' => 'Street',      'price' => 34.50, 'image' => '3.jpeg',      'isFeatured' => false],
            ['name' => 'Pokeball',    'price' => 45.00, 'image' => '4.jpeg',    'isFeatured' => true],
            ['name' => 'PinkLady',    'price' => 29.90, 'image' => '5.jpeg',    'isFeatured' => false],
            ['name' => 'Snow',        'price' => 32.00, 'image' => '6.jpeg',        'isFeatured' => false],
            ['name' => 'Greyback',    'price' => 28.50, 'image' => '7.jpeg',    'isFeatured' => false],
            ['name' => 'BlueCloud',   'price' => 45.00, 'image' => '8.jpeg',   'isFeatured' => false],
            ['name' => 'BornInUsa',   'price' => 59.90, 'image' => '9.jpeg',   'isFeatured' => true],
            ['name' => 'GreenSchool', 'price' => 42.20, 'image' => '10.jpeg', 'isFeatured' => false],
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL'];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setImage($data['image']);
            $product->setIsFeatured($data['isFeatured']);

            $manager->persist($product);

            foreach ($sizes as $size) {
                $stock = new Stock();
                $stock->setSize($size);
                $stock->setQuantity(2);
                $stock->setProduct($product);

                $manager->persist($stock);
            }
        }

        $manager->flush();
    }
}