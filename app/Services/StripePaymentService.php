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

    public function createPaymentIntent($metadata){
        try {
            $intent = PaymentIntent::create([
                'amount'=>299, // 2.99 USD
                'currency'=>'usd',
                'payment_method_types'=>['card'],
                'metadata'=>$metadata
            ]);
            return [
                'status'=>'success',
                'client_secret'=>$intent->client_secret,
                'payment_intent_id'=>$intent->id
            ];
        } catch (\Exception $e){
            return ['status'=>'error','message'=>$e->getMessage()];
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