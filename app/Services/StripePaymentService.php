<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\GarageSale;

class StripePaymentService
{
        public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ── Garage Sale ───────────────────────────────────────────────

    public function createPaymentIntent(array $metadata): array
    {
        try {
            $intent = PaymentIntent::create([
                'amount'               => 299,  // $2.99 fixed for garage
                'currency'             => 'usd',
                'payment_method_types' => ['card'],
                'metadata'             => $metadata,
            ]);

            return [
                'status'            => 'success',
                'client_secret'     => $intent->client_secret,
                'payment_intent_id' => $intent->id,
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── Spotlight Boost — amount comes from DB via SpotlightService ──

    public function createSpotlightPaymentIntent(array $metadata, float $fee): array
    {
        try {
            // Convert dollars to cents for Stripe (e.g. 2.99 → 299, 4.99 → 499)
            $amountInCents = (int) round($fee * 100);

            $intent = PaymentIntent::create([
                'amount'               => $amountInCents,
                'currency'             => 'usd',
                'payment_method_types' => ['card'],
                'metadata'             => $metadata,
            ]);

            return [
                'status'            => 'success',
                'client_secret'     => $intent->client_secret,
                'payment_intent_id' => $intent->id,
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── Utilities ─────────────────────────────────────────────────

    public function verifyPayment(string $paymentIntentId): array
    {
        try {
            $pi = PaymentIntent::retrieve($paymentIntentId);
            return match ($pi->status) {
                'succeeded'  => ['status' => 'success',    'verified' => true],
                'processing' => ['status' => 'processing', 'verified' => false],
                default      => ['status' => 'failed',     'verified' => false],
            };
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function refundPayment(string $paymentIntentId): array
    {
        try {
            $pi = PaymentIntent::retrieve($paymentIntentId);
            if ($pi->status === 'succeeded') {
                $pi->charges->data[0]->refund();
                return ['status' => 'success', 'message' => 'Refunded'];
            }
            return ['status' => 'error', 'message' => 'Cannot refund'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}