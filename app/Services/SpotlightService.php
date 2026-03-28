<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;
use App\Models\SpotlightPayment;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Log;

class SpotlightService
{
     public function __construct(protected StripePaymentService $stripe) {}

    public function getBoostFee(): float
    {
        $settings = Setting::first();
        return (float) ($settings->spotlight_fee ?? 2.99);
    }

    // public function initiateSpotlight(int $userId, int $productId): array
    // {
    //     $product = Product::findOrFail($productId);

    //     // Check if user already did a boost in last 7 days
    //     $lastBoost = Product::where('user_id', $userId)
    //         ->where('is_spotlighted', true)
    //         ->where('spotlight_start_date', '>=', now()->subDays(7))
    //         ->first();

    //     if ($lastBoost) {
    //         return [
    //             'status'=>'error',
    //             'message'=>'You can boost only 1 product per 7 days.',
    //         ];
    //     }

    //     if ($product->is_spotlighted && now()->lt($product->spotlight_end_date)) {
    //         return [
    //             'status'=>'error',
    //             'message'=>'This post is already spotlighted until '.$product->spotlight_end_date->format('d M Y, h:i A'),
    //         ];
    //     }

    //     $fee = $this->getBoostFee();
    //     $metadata = [
    //         'type'=>'spotlight',
    //         'user_id'=>$userId,
    //         'product_id'=>$productId,
    //         'boost_plan'=>'weekend_boost',
    //         'boost_hours'=>48,
    //         'amount'=>$fee,
    //     ];

    //     return $this->stripe->createSpotlightPaymentIntent($metadata, $fee);
    // }

    // public function expireSpotlights(): int
    // {
    //     $expired = Product::where('is_spotlighted', true)
    //         ->where('spotlight_end_date', '<=', now())
    //         ->get();

    //     foreach ($expired as $product) {
    //         $product->update([
    //             'is_spotlighted'=>false,
    //             'spotlight_start_date'=>null,
    //             'spotlight_end_date'=>null,
    //             'status' => Product::STATUS_EXPIRED
    //         ]);
    //     }

    //     return $expired->count();
    // }

    public function initiateSpotlight(int $userId, int $productId): array
    {
        $product = Product::find($productId);

        if (!$product || $product->status !== 'active') {
            return [
                'status' => 'error',
                'message' => 'Only active products can be boosted.'
            ];
        }

        if ($product->is_spotlighted && now()->lt($product->spotlight_end_date)) {
            return [
                'status' => 'error',
                'message' => 'Product already spotlighted.'
            ];
        }

        // 7 day boost limit
        $boosted = SpotlightPayment::where('user_id', $userId)
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();

        if ($boosted) {
            return [
                'status' => 'error',
                'message' => 'You can boost only once in 7 days.'
            ];
        }

        $fee = $this->getBoostFee();

        $metadata = [
            'type'       => 'spotlight',
            'user_id'    => $userId,
            'product_id' => $productId,
            'boost_hours' => 48,
            'boost_plan' => 'weekend_boost',
            'amount'     => $fee,
        ];

        $intent = $this->stripe->createSpotlightPaymentIntent($metadata, $fee);

        if (!isset($intent['client_secret'])) {
            return [
                'status' => 'error',
                'message' => $intent['message'] ?? 'Stripe error creating payment.'
            ];
        }

        // save pending payment record
        SpotlightPayment::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'stripe_payment_intent_id' => $intent['payment_intent_id'],
            'amount' => $fee,
            'posting_fee' => $fee,
            'total_fee' => $fee,
            'currency' => 'usd',
            'status' => 'pending',
            'boost_plan' => 'Weekend Boost',
            'boost_hours' => 48,
        ]);

        return [
            'status' => 'success',
            'client_secret' => $intent['client_secret'],
            'payment_intent_id' => $intent['payment_intent_id'],
        ];
    }

    public function expireSpotlights(): int
    {
        $products = Product::where('is_spotlighted', true)
            ->where('spotlight_end_date', '<=', now())
            ->get();

        foreach ($products as $product) {
            $product->update([
                'is_spotlighted' => false,
                'spotlight_start_date' => null,
                'spotlight_end_date' => null,
            ]);
        }

        return $products->count();
    }
}
