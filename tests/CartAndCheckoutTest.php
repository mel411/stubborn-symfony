<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartAndCheckoutTest extends WebTestCase
{
    public function testCartPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Votre panier est vide');
    }

    public function testCheckoutRedirectsWhenCartIsEmpty(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/checkout');

        $this->assertResponseRedirects('/login');
    }

}