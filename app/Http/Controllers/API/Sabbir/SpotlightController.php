<?php

namespace App\Http\Controllers\Api\Sabbir;

use App\Http\Controllers\Controller;
use App\Services\SpotlightService;
use Illuminate\Http\Request;

class SpotlightController extends Controller
{
    public function __construct(protected SpotlightService $spotlightService) {}

    /**
     * Get current boost fee (for showing on the UI screen).
     *
     * GET /api/spotlight/fee
     * Auth: Bearer token
     */
    public function getBoostFee()
    {
        $fee = $this->spotlightService->getBoostFee();

        return response()->json([
            'status' => true,
            'data'   => [
                'boost_plan'  => 'Weekend Boost',
                'boost_hours' => 48,
                'fee'         => $fee,             // e.g. 2.99
                'fee_display' => '$' . number_format($fee, 2),  // e.g. "$2.99"
                'currency'    => 'usd',
            ],
        ]);
    }

    /**
     * Initiate spotlight payment.
     * Fee is loaded from DB — not from request.
     *
     * POST /api/spotlight/initiate
     * Auth: Bearer token
     * Body: { "product_id": 5 }
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $result = $this->spotlightService->initiateSpotlight(
            auth()->id(),
            (int) $request->product_id
        );

        if ($result['status'] === 'error') {
            return response()->json([
                'status'  => false,
                'message' => $result['message'],
            ], 400);
        }

        $fee = $this->spotlightService->getBoostFee();

        return response()->json([
            'status'  => true,
            'message' => 'Spotlight payment initiated',
            'data'    => [
                'client_secret'     => $result['client_secret'],
                'payment_intent_id' => $result['payment_intent_id'],
                'amount'            => $fee,
                'fee_display'       => '$' . number_format($fee, 2),
                'currency'          => 'usd',
                'boost_hours'       => 48,
                'boost_plan'        => 'Weekend Boost',
            ],
        ]);
    }
}
