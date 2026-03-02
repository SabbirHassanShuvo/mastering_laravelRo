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

    /**
     * Create Payment Intent for Garage Sale
     */
   public function createPaymentIntent(GarageSale $garageSale)
    {
        try {

            if ($garageSale->stripe_payment_intent_id) {

                $existing = PaymentIntent::retrieve($garageSale->stripe_payment_intent_id);

                return [
                    'status' => 'success',
                    'client_secret' => $existing->client_secret,
                    'payment_intent_id' => $existing->id,
                    'amount' => $garageSale->total_fee,
                ];
            }

            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($garageSale->total_fee * 100),
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'garage_sale_id' => $garageSale->id,
                    'user_id' => $garageSale->user_id,
                ],
            ]);

            $garageSale->update([
                'stripe_payment_intent_id' => $paymentIntent->id,
                'payment_status' => 'pending',
            ]);

            return [
                'status' => 'success',
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $garageSale->total_fee,
            ];

        } catch (\Exception $e) {

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify Payment Intent Status
     */
    public function verifyPayment($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                return ['status' => 'success', 'verified' => true];
            } elseif ($paymentIntent->status === 'processing') {
                return ['status' => 'processing', 'verified' => false];
            } else {
                return ['status' => 'failed', 'verified' => false];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Refund Payment
     */
    public function refundPayment($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                // Refund through Charge
                $charge = $paymentIntent->charges->data[0];
                $charge->refund();
                
                return ['status' => 'success', 'message' => 'Payment refunded'];
            }

            return ['status' => 'error', 'message' => 'Cannot refund this payment'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}