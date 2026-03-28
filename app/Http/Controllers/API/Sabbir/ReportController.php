<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 10 — POST /api/reports
    // ─────────────────────────────────────────────────────────────────
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'reported_id' => 'required|exists:users,id',
            'reason'      => 'required|string|in:Spam,Scam,Unsafe behavior,Fake listing,Other',
            'description' => 'nullable|string|max:2000',
        ]);

        if (auth()->id() === (int) $data['reported_id']) {
            return response()->json(['status' => false, 'message' => 'Cannot report yourself.'], 422);
        }

        Report::create([
            'reporter_id' => auth()->id(),
            'reported_id' => $data['reported_id'],
            'reason'      => $data['reason'],
            'description' => $data['description'] ?? null,
        ]);

        // Automatic Verification Check (Disqualify if report exists)
        User::find($data['reported_id'])->checkVerifyStatus();

        return response()->json([
            'status'  => true,
            'message' => 'Thanks for letting us know. We will investigate.',
        ], 201);
    }
}