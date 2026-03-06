<?php

namespace App\Services;

use App\Models\Product;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Log;

class SpotlightService
{
    public function __construct(protected StripePaymentService $stripe) {}

    /**
     * Get current boost fee from DB (admin controlled).
     * Falls back to 2.99 if not set.
     */
    // public function getBoostFee(): float
    // {
    //     return (float) AppSetting::get('spotlight_boost_fee', '2.99');
    // }

    /**
     * Initiate spotlight payment.
     * Amount is taken from app_settings — not hardcoded.
     */
    public function initiateSpotlight(int $userId, int $productId): array
    {
        $product = Product::findOrFail($productId);

        // Block if already active
        if ($product->is_spotlighted && now()->lt($product->spotlight_end_date)) {
            return [
                'status'  => 'error',
                'message' => 'This post is already spotlighted until '
                    . $product->spotlight_end_date->format('d M Y, h:i A'),
            ];
        }

        // ── Fee from DB, not hardcoded ──────────────────────────
        // $fee = $this->getBoostFee();
        $fee = 2.99; // For demo, hardcoded. Replace with DB value in production.

        $metadata = [
            'type'        => 'spotlight',
            'user_id'     => $userId,
            'product_id'  => $productId,
            'boost_plan'  => 'weekend_boost',
            'boost_hours' => 48,
            'amount'      => $fee,
        ];

        return $this->stripe->createSpotlightPaymentIntent($metadata, $fee);
    }

    /**
     * Expire boosts past their end date.
     */
    public function expireSpotlights(): int
    {
        $expired = Product::where('is_spotlighted', true)
            ->where('spotlight_end_date', '<=', now())
            ->get();

        foreach ($expired as $product) {
            $product->update([
                'is_spotlighted'       => false,
                'spotlight_start_date' => null,
                'spotlight_end_date'   => null,
            ]);
            Log::info("Spotlight expired — Product #{$product->id}");
        }

        return $expired->count();
    }
}
