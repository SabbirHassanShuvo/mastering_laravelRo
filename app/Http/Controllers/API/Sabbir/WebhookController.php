<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\GarageItem;
use App\Models\GarageItemImage;
use App\Models\GarageSale;
use App\Models\Product;
use App\Models\SpotlightPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller  
{
    // public function handleWebhook(Request $request)
    // {
    //     Stripe::setApiKey(config('services.stripe.secret'));
        
    //     $endpointSecret = config('services.stripe.webhook_secret');
    //     $payload = $request->getContent();
    //     $sigHeader = $request->header('Stripe-Signature');

    //     Log::info('Webhook hit! Secret: ' . ($endpointSecret ? 'SET' : 'NOT SET'));
    //     Log::info('Sig Header: ' . ($sigHeader ?? 'MISSING'));

    //     try {
    //         $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
    //     } catch (\Exception $e) {
    //         Log::error('Webhook failed: ' . $e->getMessage());
    //         return response()->json(['error' => 'Invalid webhook'], 400);
    //     }

    //     Log::info('Event type: ' . $event->type);

    //     if ($event->type === 'payment_intent.succeeded') {
    //         $paymentIntent = $event->data->object;
    //         Log::info('PaymentIntent metadata: ' . json_encode($paymentIntent->metadata));
            
    //         try {
    //             $this->saveGarage($paymentIntent);
    //         } catch (\Exception $e) {
    //             Log::error('SaveGarage error: ' . $e->getMessage());
    //             Log::error($e->getTraceAsString());
    //         }
    //     }

    //     return response()->json(['success' => true]);
    // }

    // private function saveGarage($paymentIntent)
    // {
    //     $metadata = $paymentIntent->metadata;

    //     // Duplicate check
    //     if (GarageSale::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
    //         Log::info('Duplicate, skipping: ' . $paymentIntent->id);
    //         return;
    //     }

    //     Log::info('Metadata received: ' . json_encode($metadata->toArray()));

    //     // Create Garage Sale
    //     $garage = GarageSale::create([
    //         'user_id'                  => $metadata->user_id,
    //         'event_title'              => $metadata->event_title ?? 'Garage Sale',
    //         'description'              => $metadata->description ?? '',
    //         'date'                     => $metadata->date,
    //         'pickup_location'          => $metadata->pickup_location,
    //         'sale_start_date'          => $metadata->sale_start_date,
    //         'sale_end_date'            => $metadata->sale_end_date,
    //         'latitude'                 => $metadata->latitude ?: null,
    //         'longitude'                => $metadata->longitude ?: null,
    //         'expires_at'               => $metadata->expires_at,
    //         'total_fee'                => 2.99,
    //         'status'                   => 'active',
    //         'payment_status'           => 'completed',
    //         'payment_completed_at'     => now(),
    //         'stripe_payment_intent_id' => $paymentIntent->id,
    //     ]);

    //     $items = json_decode($metadata->items, true);

    //     foreach ($items as $itemData) {
    //         $item = GarageItem::create([
    //             'garage_sale_id' => $garage->id,
    //             'title'          => $itemData['title'],
    //             'price'          => $itemData['price'] ?? null,
    //             'description'    => $itemData['description'] ?? null,
    //         ]);

    //         if (!empty($itemData['images'])) {
    //             foreach ($itemData['images'] as $img) {
    //                 // Images are already stored paths or URLs — save directly
    //                 GarageItemImage::create([
    //                     'garage_item_id' => $item->id,
    //                     'photo'          => $img,
    //                 ]);
    //             }
    //         }
    //     }

    //     Log::info("GarageSale #{$garage->id} saved! PaymentIntent: {$paymentIntent->id}");
    // }

     /**
     * Single Stripe webhook entry point.
     * Routes by metadata.type
     *
     * POST /api/webhooks/stripe
     */
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error('Webhook signature failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info('Stripe event: ' . $event->type);

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $type          = $paymentIntent->metadata->type ?? 'garage';

            try {
                match ($type) {
                    'spotlight' => $this->productWebhookHandler($paymentIntent),
                    default     => $this->garageWebhookHandler($paymentIntent),
                };
            } catch (\Exception $e) {
                Log::error("Handler [{$type}] error: " . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        }

        return response()->json(['success' => true]);
    }

    // =========================================================
    //  GARAGE HANDLER
    // =========================================================

    private function garageWebhookHandler($paymentIntent): void
    {
        $metadata = $paymentIntent->metadata;

        if (GarageSale::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
            Log::info('Garage duplicate, skipping: ' . $paymentIntent->id);
            return;
        }

        $garage = GarageSale::create([
            'user_id'                  => $metadata->user_id,
            'event_title'              => $metadata->event_title      ?? 'Garage Sale',
            'description'              => $metadata->description      ?? '',
            'date'                     => $metadata->date,
            'pickup_location'          => $metadata->pickup_location,
            'sale_start_date'          => $metadata->sale_start_date,
            'sale_end_date'            => $metadata->sale_end_date,
            'latitude'                 => $metadata->latitude         ?: null,
            'longitude'                => $metadata->longitude        ?: null,
            'expires_at'               => $metadata->expires_at,
            'total_fee'                => 2.99,
            'status'                   => 'active',
            'payment_status'           => 'completed',
            'payment_completed_at'     => now(),
            'stripe_payment_intent_id' => $paymentIntent->id,
        ]);

        foreach (json_decode($metadata->items, true) as $itemData) {
            $item = GarageItem::create([
                'garage_sale_id' => $garage->id,
                'title'          => $itemData['title'],
                'price'          => $itemData['price']       ?? null,
                'description'    => $itemData['description'] ?? null,
            ]);
            foreach ($itemData['images'] ?? [] as $img) {
                GarageItemImage::create(['garage_item_id' => $item->id, 'photo' => $img]);
            }
        }

        Log::info("GarageSale #{$garage->id} saved. PI: {$paymentIntent->id}");
    }

    // =========================================================
    //  PRODUCT / SPOTLIGHT HANDLER
    // =========================================================

    private function productWebhookHandler($paymentIntent): void
    {
        $metadata = $paymentIntent->metadata;

        if (SpotlightPayment::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
            Log::info('Spotlight duplicate, skipping: ' . $paymentIntent->id);
            return;
        }

        $boostHours = (int)   ($metadata->boost_hours ?? 48);
        $startAt    = now();
        $endAt      = now()->addHours($boostHours);

        // Amount stored in metadata (set from DB fee at time of payment creation)
        $fee = (float) ($metadata->amount ?? 2.99);

        // 1. Save payment record
        SpotlightPayment::create([
            'user_id'                  => $metadata->user_id,
            'product_id'               => $metadata->product_id,
            'stripe_payment_intent_id' => $paymentIntent->id,
            'amount'                   => $fee,
            'currency'                 => 'usd',
            'boost_plan'               => $metadata->boost_plan ?? 'weekend_boost',
            'boost_hours'              => $boostHours,
            'status'                   => 'paid',
            'spotlight_start_at'       => $startAt,
            'spotlight_end_at'         => $endAt,
        ]);

        // 2. Activate spotlight on product
        Product::where('id', $metadata->product_id)->update([
            'is_spotlighted'       => true,
            'spotlight_start_date' => $startAt,
            'spotlight_end_date'   => $endAt,
            'boost_fee'            => $fee,               // fee stored from metadata
            'boost_count'          => DB::raw('boost_count + 1'),
        ]);

        Log::info("Spotlight ON — Product #{$metadata->product_id}, Fee: \${$fee}, Ends: {$endAt}");
    }
}