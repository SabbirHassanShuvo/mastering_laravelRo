<?php

namespace App\Http\Controllers\Api\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SpotlightPayment;
use App\Services\SpotlightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class SpotlightController extends Controller
{
    public function __construct(protected SpotlightService $spotlightService) {}

    public function getBoostFee()
    {
        $fee = $this->spotlightService->getBoostFee();

        return response()->json([
            'status' => true,
            'data'   => [
                'boost_plan'  => 'Weekend Boost',
                'boost_hours' => 48,
                'fee'         => $fee,
                'fee_display' => '$' . number_format($fee, 2),
                'currency'    => 'usd',
            ],
        ]);
    }

    public function initiatePayment(Request $request)
{
    $request->validate([
        'product_id' => 'required|integer|exists:products,id'
    ]);

    $result = $this->spotlightService->initiateSpotlight(auth()->id(), $request->product_id);

// dd($result);

    // check if service returned error
    if (!isset($result['status']) || $result['status'] === 'error') {
        return response()->json([
            'status'  => false,
            'message' => $result['message'] ?? 'Unable to initiate spotlight payment'
        ], 400);
    }

    $fee = $this->spotlightService->getBoostFee();

    return response()->json([
        'status'  => true,
        'message' => 'Spotlight payment initiated',
        'data'    => [
            'client_secret'     => $result['client_secret'] ?? null,
            'payment_intent_id' => $result['payment_intent_id'] ?? null,
            'amount'            => $fee,
            'fee_display'       => '$' . number_format($fee, 2),
            'currency'          => 'usd',
            'boost_hours'       => 48,
            'boost_plan'        => 'Weekend Boost',
        ],
    ]);
}

    public function confirmPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors()->first()
            ],422);
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($paymentIntent->status === 'succeeded') {
            return response()->json([
                'status'=>true,
                'message'=>'Payment already completed. Spotlight will activate via webhook.',
                'data'=>[
                    'payment_intent_id'=>$paymentIntent->id,
                    'payment_status'=>$paymentIntent->status,
                    'amount'=>($paymentIntent->amount/100).' USD',
                ]
            ]);
        }

        $paymentIntent->confirm(['payment_method'=>'pm_card_visa']);
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($paymentIntent->status === 'succeeded') {
            return response()->json([
                'status'=>true,
                'message'=>'Payment confirmed! Spotlight will activate via webhook.',
                'data'=>[
                    'payment_intent_id'=>$paymentIntent->id,
                    'payment_status'=>$paymentIntent->status,
                    'amount'=>($paymentIntent->amount/100).' USD',
                ]
            ]);
        }

        return response()->json([
            'status'=>false,
            'message'=>'Payment failed. Status: '.$paymentIntent->status,
        ],400);
    }

  // Confirm Stripe Payment and activate spotlight
    // public function confirmPayment(Request $request)
    // {
    //     $request->validate(['payment_intent_id' => 'required|string']);

    //     $paymentIntent = $this->stripe->retrievePaymentIntent($request->payment_intent_id);

    //     \Log::info('Stripe PaymentIntent:', [
    //         'status' => $paymentIntent->status,
    //         'metadata' => $paymentIntent->metadata
    //     ]);

    //     if (in_array($paymentIntent->status, ['succeeded', 'requires_capture'])) {
    //         $productId = $paymentIntent->metadata->product_id ?? null;
    //         if (!$productId) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Product ID missing in payment metadata.'
    //             ], 400);
    //         }

    //         $product = Product::find($productId);
    //         if (!$product) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Product not found.'
    //             ], 404);
    //         }

    //         // Update product spotlight
    //         $product->update([
    //             'is_spotlighted' => true,
    //             'spotlight_start_date' => now(),
    //             'spotlight_end_date' => now()->addHours(48),
    //             'status' => Product::STATUS_ACTIVE,
    //             'boost_count' => $product->boost_count + 1,
    //             'boost_fee' => $paymentIntent->amount / 100
    //         ]);

    //         // Save payment record
    //         SpotlightPayment::updateOrCreate(
    //             ['stripe_payment_intent_id' => $paymentIntent->id],
    //             [
    //                 'user_id' => $product->user_id,
    //                 'product_id' => $product->id,
    //                 'stripe_payment_method_id' => $paymentIntent->payment_method ?? null,
    //                 'amount' => $paymentIntent->amount / 100,
    //                 'currency' => $paymentIntent->currency,
    //                 'boost_plan' => $paymentIntent->metadata->boost_plan ?? 'weekend_boost',
    //                 'boost_hours' => $paymentIntent->metadata->boost_hours ?? 48,
    //                 'status' => 'paid',
    //                 'spotlight_start_at' => now(),
    //                 'spotlight_end_at' => now()->addHours(48),
    //             ]
    //         );

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Spotlight activated! Product will stay active 48 hours.',
    //             'data' => $product
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Payment not successful. Current status: ' . $paymentIntent->status
    //     ], 400);
    // }
}