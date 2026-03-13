<?php

namespace App\Service;

class StripeService
{
    public function createCheckoutSession(float $amount): string
    {
        // Normally this would call Stripe API
        // For the project we simulate a Stripe session

        return 'stripe_session_' . uniqid();
    }
}