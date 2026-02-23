<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
     public function profileRetrieval(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized.'
        ], 401);
    }

    $user->load(['products.photos', 'products.category']);

    return response()->json([
        'success' => true,
        'message' => 'User profile retrieved successfully.',
        'data' => [
            'user' => $user->only([
                'id',
                'name',
                'email',
                'avatar',
                'address',
                'phone',
                'role',
                'is_premium'
            ]),
            'posts' => $user->products
        ]
    ], 200);
}


    
    public function profileUpdate(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return jsonErrorResponse('Unauthenticated.', 401);
        }

        // Create profile if not exists
        if (!$user->profile) {
            $user->profile()->create([]);
        }

        $profile = $user->profile;

        $validator = Validator::make($request->all(), [

            // Users table fields
            'name' => 'sometimes|nullable|string|max:255',

            // Profile table fields
            'user_name' => 'sometimes|nullable|string|max:255',
            'profile_image' => 'sometimes|nullable|string',
            'bio' => 'sometimes|nullable|string|max:500',
            'phone' => 'sometimes|nullable|string|max:20',
            'pickup_location' => 'sometimes|nullable|string|max:255',
            'latitude' => 'sometimes|nullable|string',
            'longitude' => 'sometimes|nullable|string',
            'search_radius_km' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return jsonErrorResponse(
                'Profile Update Validation failed',
                422,
                $validator->errors()->toArray()
            );
        }

        // ----------------------
        // Update Users table
        // ----------------------
        $user->update($request->only([
            'name',
            'email',
            'is_admin_user',
            'role',
            'status',
            'is_verified',
            'is_active'
        ]));

        // ----------------------
        // Avatar Upload
        // ----------------------
        if ($request->hasFile('avatar')) {

            if ($profile->avatar) {
                fileDelete(public_path($profile->avatar));
            }

            $avatar = $request->file('avatar');
            $avatarName = $user->id . '_avatar';
            $avatarPath = fileUpload($avatar, 'profile/avatar', $avatarName);

            $profile->avatar = $avatarPath;
        }

        // ----------------------
        // Banner Upload
        // ----------------------
        if ($request->hasFile('banner')) {

            if ($profile->banner) {
                fileDelete(public_path($profile->banner));
            }

            $banner = $request->file('banner');
            $bannerName = $user->id . '_banner';
            $bannerPath = fileUpload($banner, 'profile/banner', $bannerName);

            $profile->banner = $bannerPath;
        }

        // ----------------------
        // Update Profile table
        // ----------------------
        $profile->update($request->only([
            'address',
            'user_name',
            'profile_image',
            'bio',
            'phone',
            'user_type',
            'pickup_location',
            'latitude',
            'longitude',
            'search_radius_km'
        ]));

        return jsonResponse(
            true,
            'User & Profile updated successfully.',
            200,
            [
                'user' => $user,
                'profile' => $profile
            ]
        );
    }

    public function ChangePassword(Request $request)
    {
        // Create custom validator using Validator facade
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        // Authenticate the user using JWT
        // $user = JWTAuth::parseToken()->authenticate();
        $user = auth('api')->user();

        if (!$user) {
            return jsonErrorResponse('User not found or unauthorized', 401);
        }

        // Check if the old password matches the current password
        if (!Hash::check($request->old_password, $user->password)) {
            return jsonErrorResponse('Old password is incorrect', 400);
        }

        // Hash the new password and save it to the database
        $user->password = Hash::make($request->password);
        $user->save();

        return jsonResponse(true, 'Password changed successfully', 200, $user->only(['name', 'email', 'avatar']));
    }

public function notifications(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    }

    $notifications = $user->notifications()
        ->latest()
        ->get()
        ->map(function ($notification) {

            $productData = null;

            if (isset($notification->data['product_id'])) {
                $product = Product::find($notification->data['product_id']);

                if ($product) {
                    // relation name change করা যাবে না, তাই map করে অন্য key তে পাঠাচ্ছি
                    $productData = [
                        'id' => $product->id,
                        'name' => $product->name ?? null,
                        'status' => $product->status ?? null,
                        'images' => $product->photos->map(function($photo){
                            return $photo->photo;
                        }) // শুধু image path
                    ];
                }
            }

            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'product' => $productData
            ];
        });

    return response()->json([
        'success' => true,
        'notifications' => $notifications
    ]);
}

}
