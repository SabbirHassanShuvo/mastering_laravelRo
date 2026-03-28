<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 9 — POST /api/reviews
    // ─────────────────────────────────────────────────────────────────
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pickup_id' => 'required|exists:pickups,id',
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'nullable|string|max:1000',
        ]);

        $pickup = Pickup::findOrFail($data['pickup_id']);
        $authId = auth()->id();

        if (!$pickup->hasUser($authId)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($pickup->status !== 'completed') {
            return response()->json(['status' => false, 'message' => 'Only completed pickups can be reviewed.'], 422);
        }

        $alreadyReviewed = Review::where('pickup_id', $pickup->id)
            ->where('reviewer_id', $authId)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json(['status' => false, 'message' => 'You already reviewed this pickup.'], 409);
        }

        $review = Review::create([
            'pickup_id'   => $pickup->id,
            'product_id'  => $pickup->product_id,
            'reviewer_id' => $authId,
            'reviewee_id' => $pickup->otherUserId($authId),
            'rating'      => $data['rating'],
            'comment'     => $data['comment'] ?? null,
        ]);

        // Automatic Verification Check
        $review->reviewee->checkVerifyStatus();

        return response()->json(['status' => true, 'message' => 'Thank you for your review.'], 201);
    }

    // GET /api/users/{id}/reviews
    public function userReviews(int $userId): JsonResponse
    {
        $reviews = Review::with([
            'reviewer:id,name',
            'reviewer.profile:id,user_id,avatar',
            'product:id,title'
        ])
            ->where('reviewee_id', $userId)
            ->latest()
            ->get()
            ->map(function ($review) {
                return [
                    'id'          => $review->id,
                    'rating'      => $review->rating,
                    'comment'     => $review->comment,
                    'created_at'  => $review->created_at,
                    'reviewer'    => [
                        'id'     => $review->reviewer->id,
                        'name'   => $review->reviewer->name,
                        'avatar' => $review->reviewer->profile->avatar ?? null,
                    ],
                    'product'     => $review->product,
                ];
            });

        $avg = $reviews->avg('rating');

        return response()->json([
            'status' => true,
            'data'   => [
                'average_rating' => round($avg, 1),
                'total_reviews'  => $reviews->count(),
                'reviews'        => $reviews,
            ],
        ]);
    }
}