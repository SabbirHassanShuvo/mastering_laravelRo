<?php

namespace App\Services;

use App\Models\GarageSale;
use App\Models\Setting;
use Stripe\PaymentIntent;
use Stripe\Stripe;

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
            $settings = Setting::first();
            $amount = ($settings->garage_fee ?? 2.99) * 100;

            $intent = PaymentIntent::create([
                'amount'               => (int) $amount,
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

    // Retrieve PaymentIntent from Stripe
    public function retrievePaymentIntent(string $paymentIntentId)
    {
        return PaymentIntent::retrieve($paymentIntentId);
    }

    // Create PaymentIntent for Spotlight
    public function createSpotlightPaymentIntent(array $metadata, float $amount): array
    {
        try {
            $intent = \Stripe\PaymentIntent::create([
                'amount' => (int) round($amount * 100),
                'currency' => 'usd',
                'metadata' => $metadata,
                'payment_method_types' => ['card'],
            ]);

            return [
                'client_secret' => $intent->client_secret,
                'payment_intent_id' => $intent->id,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
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