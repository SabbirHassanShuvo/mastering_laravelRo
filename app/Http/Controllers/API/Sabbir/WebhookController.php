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
    
     /**
     * Single Stripe webhook entry point.
     * Routes by metadata.type
     *
     * POST /api/webhooks/stripe
     */
    // public function handleWebhook(Request $request)
    // {
    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     $payload   = $request->getContent();
    //     $sigHeader = $request->header('Stripe-Signature');
    //     $secret    = config('services.stripe.webhook_secret');

    //     try {
    //         $event = Webhook::constructEvent($payload, $sigHeader, $secret);
    //     } catch (\Exception $e) {
    //         Log::error('Webhook signature failed: ' . $e->getMessage());
    //         return response()->json(['error' => 'Invalid signature'], 400);
    //     }

    //     Log::info('Stripe event: ' . $event->type);

    //     if ($event->type === 'payment_intent.succeeded') {
    //         $paymentIntent = $event->data->object;
    //         $type          = $paymentIntent->metadata->type ?? 'garage';

    //         try {
    //             match ($type) {
    //                 'spotlight' => $this->productWebhookHandler($paymentIntent),
    //                 default     => $this->garageWebhookHandler($paymentIntent),
    //             };
    //         } catch (\Exception $e) {
    //             Log::error("Handler [{$type}] error: " . $e->getMessage());
    //             Log::error($e->getTraceAsString());
    //         }
    //     }

    //     return response()->json(['success' => true]);
    // }

    // // =========================================================
    // //  GARAGE HANDLER
    // // =========================================================

    // private function garageWebhookHandler($paymentIntent): void
    // {
    //     $metadata = $paymentIntent->metadata;

    //     if (GarageSale::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
    //         Log::info('Garage duplicate, skipping: ' . $paymentIntent->id);
    //         return;
    //     }

    //     $garage = GarageSale::create([
    //         'user_id'                  => $metadata->user_id,
    //         'event_title'              => $metadata->event_title      ?? 'Garage Sale',
    //         'description'              => $metadata->description      ?? '',
    //         'date'                     => $metadata->date,
    //         'pickup_location'          => $metadata->pickup_location,
    //         'sale_start_date'          => $metadata->sale_start_date,
    //         'sale_end_date'            => $metadata->sale_end_date,
    //         'latitude'                 => $metadata->latitude         ?: null,
    //         'longitude'                => $metadata->longitude        ?: null,
    //         'expires_at'               => $metadata->expires_at,
    //         'total_fee'                => 2.99,
    //         'status'                   => 'active',
    //         'payment_status'           => 'completed',
    //         'payment_completed_at'     => now(),
    //         'stripe_payment_intent_id' => $paymentIntent->id,
    //     ]);

    //     foreach (json_decode($metadata->items, true) as $itemData) {
    //         $item = GarageItem::create([
    //             'garage_sale_id' => $garage->id,
    //             'title'          => $itemData['title'],
    //             'price'          => $itemData['price']       ?? null,
    //             'description'    => $itemData['description'] ?? null,
    //         ]);
    //         foreach ($itemData['images'] ?? [] as $img) {
    //             GarageItemImage::create(['garage_item_id' => $item->id, 'photo' => $img]);
    //         }
    //     }

    //     Log::info("GarageSale #{$garage->id} saved. PI: {$paymentIntent->id}");
    // }

    // // =========================================================
    // //  PRODUCT / SPOTLIGHT HANDLER
    // // =========================================================

    // private function productWebhookHandler($paymentIntent): void
    // {
    //     $metadata = $paymentIntent->metadata;

    //     if (SpotlightPayment::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
    //         Log::info('Spotlight duplicate, skipping: ' . $paymentIntent->id);
    //         return;
    //     }

    //     $boostHours = (int)   ($metadata->boost_hours ?? 48);
    //     $startAt    = now();
    //     $endAt      = now()->addHours($boostHours);

    //     // Amount stored in metadata (set from DB fee at time of payment creation)
    //     $fee = (float) ($metadata->amount ?? 2.99);

    //     // 1. Save payment record
    //     SpotlightPayment::create([
    //         'user_id'                  => $metadata->user_id,
    //         'product_id'               => $metadata->product_id,
    //         'stripe_payment_intent_id' => $paymentIntent->id,
    //         'amount'                   => $fee,
    //         'currency'                 => 'usd',
    //         'boost_plan'               => $metadata->boost_plan ?? 'weekend_boost',
    //         'boost_hours'              => $boostHours,
    //         'status'                   => 'paid',
    //         'spotlight_start_at'       => $startAt,
    //         'spotlight_end_at'         => $endAt,
    //     ]);

    //     // 2. Activate spotlight on product
    //     Product::where('id', $metadata->product_id)->update([
    //         'is_spotlighted'       => true,
    //         'spotlight_start_date' => $startAt,
    //         'spotlight_end_date'   => $endAt,
    //         'boost_fee'            => $fee,               // fee stored from metadata
    //         'boost_count'          => DB::raw('boost_count + 1'),
    //     ]);

    //     Log::info("Spotlight ON — Product #{$metadata->product_id}, Fee: \${$fee}, Ends: {$endAt}");
    // }

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
            $pi   = $event->data->object;
            $type = $pi->metadata->type ?? 'garage';

            try {
                match ($type) {
                    'spotlight'      => $this->productWebhookHandler($pi),
                    'garage_payload' => $this->garageWebhookHandler($pi),
                    default          => $this->garageWebhookHandler($pi),
                };
            } catch (\Exception $e) {
                Log::error("Webhook handler [{$type}] failed: " . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        }

        return response()->json(['success' => true]);
    }

    private function garageWebhookHandler($pi): void
    {
        $metadata = $pi->metadata;

        // 1. Check for duplicate payment intent
        if (GarageSale::where('stripe_payment_intent_id', $pi->id)->exists()) {
            Log::info('Garage duplicate, skipping: ' . $pi->id);
            return;
        }

        // 2. Main Flow: Using local storage JSON payload
        if (isset($metadata->payload_id)) {
            $payloadFile = "stripe_payloads/{$metadata->payload_id}.json";
            
            if (!Storage::disk('local')->exists($payloadFile)) {
                Log::error("Garage payload file not found: {$payloadFile}. PI: {$pi->id}");
                return;
            }

            $payloadContent = Storage::disk('local')->get($payloadFile);
            $fullData = json_decode($payloadContent, true);

            // Create GarageSale record
            $garage = GarageSale::create([
                'user_id'                  => $fullData['user_id'],
                'event_title'              => $fullData['event_title'] ?? 'Garage Sale',
                'description'              => $fullData['description'] ?? '',
                'date'                     => $fullData['date'],
                'pickup_location'          => $fullData['pickup_location'],
                'sale_start_date'          => $fullData['sale_start_date'],
                'sale_end_date'            => $fullData['sale_end_date'],
                'latitude'                 => $fullData['latitude'] ?: null,
                'longitude'                => $fullData['longitude'] ?: null,
                'expires_at'               => $fullData['expires_at'],
                'total_fee'                => 2.99,
                'status'                   => 'active',
                'payment_status'           => 'completed',
                'payment_completed_at'     => now(),
                'stripe_payment_intent_id' => $pi->id,
            ]);

            $itemsList = is_string($fullData['items']) ? json_decode($fullData['items'], true) : $fullData['items'];

            Log::info("Processing items for GarageSale #{$garage->id}", ['items_count' => count($itemsList)]);

            foreach ($itemsList as $itemData) {
                $item = GarageItem::create([
                    'garage_sale_id' => $garage->id,
                    'title'          => $itemData['title'],
                    'price'          => $itemData['price'] ?? null,
                    'description'    => $itemData['description'] ?? null,
                ]);
                
                $images = $itemData['images'] ?? [];
                foreach ($images as $img) {
                    try {
                        GarageItemImage::create(['garage_item_id' => $item->id, 'photo' => $img]);
                    } catch (\Exception $e) {
                        Log::error("Failed to insert image for item #{$item->id}: " . $e->getMessage());
                    }
                }
            }

            // Cleanup the file
            Storage::disk('local')->delete($payloadFile);

            Log::info("GarageSale #{$garage->id} saved via payload JSON. PI: {$pi->id}");
            return;
        }

        // 3. Backward compatibility (If someone sent metadata directly without payload_id)
        if (isset($metadata->user_id)) {
            $garage = GarageSale::create([
                'user_id'                  => $metadata->user_id,
                'event_title'              => $metadata->event_title ?? 'Garage Sale',
                'description'              => $metadata->description ?? '',
                'date'                     => $metadata->date,
                'pickup_location'          => $metadata->pickup_location,
                'sale_start_date'          => $metadata->sale_start_date,
                'sale_end_date'            => $metadata->sale_end_date,
                'latitude'                 => $metadata->latitude ?: null,
                'longitude'                => $metadata->longitude ?: null,
                'expires_at'               => $metadata->expires_at,
                'total_fee'                => 2.99,
                'status'                   => 'active',
                'payment_status'           => 'completed',
                'payment_completed_at'     => now(),
                'stripe_payment_intent_id' => $pi->id,
            ]);

            if (isset($metadata->items)) {
                foreach (json_decode($metadata->items, true) as $itemData) {
                    $item = GarageItem::create([
                        'garage_sale_id' => $garage->id,
                        'title'          => $itemData['title'],
                        'price'          => $itemData['price'] ?? null,
                        'description'    => $itemData['description'] ?? null,
                    ]);
                    foreach ($itemData['images'] ?? [] as $img) {
                        GarageItemImage::create(['garage_item_id' => $item->id, 'photo' => $img]);
                    }
                }
            }
            Log::info("GarageSale #{$garage->id} saved (direct metadata fallback). PI: {$pi->id}");
            return;
        }

        Log::error("No valid metadata or payload found for Garage payment activation. PI: {$pi->id}");
    }

    private function productWebhookHandler($pi): void
    {
        $metadata = $pi->metadata;

        Log::info('productWebhookHandler fired', [
            'pi_id'      => $pi->id,
            'product_id' => $metadata->product_id ?? null,
            'user_id'    => $metadata->user_id ?? null,
        ]);

        // SpotlightService already creates a PENDING record.
        // Only skip if a PAID record already exists (true duplicate).
        $alreadyPaid = SpotlightPayment::where('stripe_payment_intent_id', $pi->id)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyPaid) {
            Log::info('Spotlight duplicate (already paid), skipping: ' . $pi->id);
            return;
        }

        $boostHours = (int) ($metadata->boost_hours ?? 48);
        $startAt    = now();
        $endAt      = now()->addHours($boostHours);
        $fee        = (float) ($metadata->amount ?? 2.99);

        // Update existing pending record → paid, OR create new if none exists
        SpotlightPayment::updateOrCreate(
            ['stripe_payment_intent_id' => $pi->id],
            [
                'user_id'            => $metadata->user_id,
                'product_id'         => $metadata->product_id,
                'amount'             => $fee,
                'currency'           => 'usd',
                'boost_plan'         => $metadata->boost_plan ?? 'weekend_boost',
                'boost_hours'        => $boostHours,
                'status'             => 'paid',
                'spotlight_start_at' => $startAt,
                'spotlight_end_at'   => $endAt,
            ]
        );

        // Activate spotlight on product
        $updated = Product::where('id', $metadata->product_id)->update([
            'is_spotlighted'       => true,
            'spotlight_start_date' => $startAt,
            'spotlight_end_date'   => $endAt,
            'boost_fee'            => $fee,
            'boost_count'          => DB::raw('boost_count + 1'),
            'expires_at'           => now()->addHours(72),
            'status'               => 'active',
        ]);

        Log::info("Spotlight ON — Product #{$metadata->product_id}, rows updated: {$updated}, Ends: {$endAt}");
    }
}