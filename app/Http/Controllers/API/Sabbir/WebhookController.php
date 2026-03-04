<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\GarageItem;
use App\Models\GarageItemImage;
use App\Models\GarageSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller  
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $endpointSecret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        Log::info('Webhook hit! Secret: ' . ($endpointSecret ? 'SET' : 'NOT SET'));
        Log::info('Sig Header: ' . ($sigHeader ?? 'MISSING'));

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Webhook failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid webhook'], 400);
        }

        Log::info('Event type: ' . $event->type);

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            Log::info('PaymentIntent metadata: ' . json_encode($paymentIntent->metadata));
            
            try {
                $this->saveGarage($paymentIntent);
            } catch (\Exception $e) {
                Log::error('SaveGarage error: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        }

        return response()->json(['success' => true]);
    }

    private function saveGarage($paymentIntent)
    {
        $metadata = $paymentIntent->metadata;

        // Duplicate check
        if (GarageSale::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
            Log::info('Duplicate, skipping: ' . $paymentIntent->id);
            return;
        }

        Log::info('Metadata received: ' . json_encode($metadata->toArray()));

        // Create Garage Sale
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
            'stripe_payment_intent_id' => $paymentIntent->id,
        ]);

        $items = json_decode($metadata->items, true);

        foreach ($items as $itemData) {
            $item = GarageItem::create([
                'garage_sale_id' => $garage->id,
                'title'          => $itemData['title'],
                'price'          => $itemData['price'] ?? null,
                'description'    => $itemData['description'] ?? null,
            ]);

            if (!empty($itemData['images'])) {
                foreach ($itemData['images'] as $img) {
                    // Images are already stored paths or URLs — save directly
                    GarageItemImage::create([
                        'garage_item_id' => $item->id,
                        'photo'          => $img,
                    ]);
                }
            }
        }

        Log::info("GarageSale #{$garage->id} saved! PaymentIntent: {$paymentIntent->id}");
    }
}