<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\GarageSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $endpointSecret = config('services.stripe.webhook_secret');

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid webhook'], 400);
        }

        switch ($event->type) {

            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            case 'charge.refunded':
                $this->handleRefund($event->data->object);
                break;
        }

        return response()->json(['success' => true]);
    }

    private function handlePaymentSucceeded($paymentIntent)
    {
        $garageSaleId = $paymentIntent->metadata->garage_sale_id ?? null;

        if (!$garageSaleId) return;

        $garage = GarageSale::find($garageSaleId);

        if (!$garage) return;

        if ($garage->payment_status !== 'completed') {
            $garage->update([
                'payment_status' => 'completed',
                'payment_completed_at' => now(),
                'status' => 'active'
            ]);

            Log::info("Payment completed for GarageSale ID: {$garageSaleId}");
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        $garageSaleId = $paymentIntent->metadata->garage_sale_id ?? null;

        if (!$garageSaleId) return;

        $garage = GarageSale::find($garageSaleId);

        if ($garage) {
            $garage->update([
                'payment_status' => 'failed'
            ]);

            Log::warning("Payment failed for GarageSale ID: {$garageSaleId}");
        }
    }

    private function handleRefund($charge)
    {
        $garageSaleId = $charge->metadata->garage_sale_id ?? null;

        if (!$garageSaleId) return;

        $garage = GarageSale::find($garageSaleId);

        if ($garage) {
            $garage->update([
                'payment_status' => 'refunded'
            ]);

            Log::info("Payment refunded for GarageSale ID: {$garageSaleId}");
        }
    }
}