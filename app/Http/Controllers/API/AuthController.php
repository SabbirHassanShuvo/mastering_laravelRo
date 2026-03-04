<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Rules\PasswordRule;

class AuthController extends BaseController
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /** Register a User.
     * @return \Illuminate\Http\JsonResponse */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'interests' => $request->interests,
        ]);

        // Auto login after register
        $token = auth('api')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user
            ]
        ], 201);
    }

  
  
    /** Get a JWT via given credentials.
     * @return \Illuminate\Http\JsonResponse */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $user = auth('api')->user();
        $user->last_login_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user
            ]
        ]);
    }

  
    /** Get the authenticated User.
     * @return \Illuminate\Http\JsonResponse */
    public function profile()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $user->load('profile');

        return response()->json([
            'success' => true,
            'message' => 'User profile retrieved successfully.',
            'data' => $user
        ], 200);
    }
  
    /** Log the user out (Invalidate the token).
     * @return \Illuminate\Http\JsonResponse */
    public function logout()
    {
        // Invalidate the token
        auth('api')->logout();

        // Professional JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully.'
        ], 200);
    }

  
    /** Refresh a token.
     * @return \Illuminate\Http\JsonResponse */
    public function refresh()
    {
        $success = $this->respondWithToken(auth('api')->refresh());
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /** Get the token array structure.
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    // public function forgotPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //     ]);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
    //     }

    //     if ($validator->fails()) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => $validator->errors()->first(),
    //     ], 422);
    //     }

    //     // Find user by email
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return jsonErrorResponse('No user found with this email address.', 404);
    //     }

    //     // Generate a 4-digit reset token
    //     $otp = $this->otpService->generateOtp($request->email);

    //     // Store the token and expiry time in the database
    //     $user->password_reset_otp = $otp;
    //     $user->password_reset_otp_is_verified = false;
    //     $user->password_reset_otp_expiry = now()->addMinutes( $this->otpService->getTtl_min_time());  // Token expires after 5 minutes
    //     $user->save();

    //     // Send token to the user's email (using Queue)
    //     // Mail::to($user->email)->queue(new PasswordResetMail($token));
    //     $this->otpService->sendOtpEmail($request->email, $otp);


    //     return jsonResponse(true, 'Password reset OTP has been sent to your email.', 200, ['OTP' => $user->password_reset_token]);
    // }

    public function forgotPassword(Request $request)
    {
        // Simple validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(), // first error only
            ], 422);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found with this email address.',
            ], 404);
        }

        // Generate a 4-digit OTP
        $otp = $this->otpService->generateOtp($request->email);

        // Store OTP in DB
        $user->password_reset_otp = $otp;
        $user->password_reset_otp_is_verified = false;
        $user->password_reset_otp_expiry = now()->addMinutes($this->otpService->getTtl_min_time());
        $user->save();

        // Send OTP to user's email
        $this->otpService->sendOtpEmail($request->email, $otp);

        // Response
        return response()->json([
            'success' => true,
            'message' => 'Password reset OTP has been sent to your email.',
        ], 200);
    }


    public function verifyOtp(Request $request)
    {
        // Simple validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found with this email address.',
            ], 404);
        }

        // Check if OTP exists
        if (!$user->password_reset_otp) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized OTP.',
            ], 401);
        }

        // Check if OTP expired
        if ($user->password_reset_otp_expiry < now()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired.',
            ], 400);
        }

        // Check if OTP matches
        if ($user->password_reset_otp !== removeSpaces($request->otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.',
            ], 400);
        }

        // Mark OTP as verified & extend expiry
        $user->password_reset_otp_is_verified = true;
        $user->password_reset_otp_expiry = now()->addMinutes(5);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully. You can now reset your password within the next 5 minutes.',
        ], 200);
    }


    public function resetPassword(Request $request)
    {
        // Step 1: Simple validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => ['required', 'string', new PasswordRule],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(), // first error only
            ], 422);
        }

        // Step 2: Find the user by email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this email address.',
            ], 404);
        }

        // Step 3: Check if OTP is verified
        if (!$user->password_reset_otp_is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not verified. Please complete verification first.',
            ], 401);
        }

        // Step 4: Check if OTP exists and is valid
        if (!$user->password_reset_otp || $user->password_reset_otp_expiry < now()) {
            $user->password_reset_otp_is_verified = false;
            $user->save();

            return response()->json([
                'success' => false,
                'message' => 'OTP expired or invalid. Please request a new OTP.',
            ], 400);
        }

        // Step 5: Reset password
        $user->password = Hash::make($request->password);
        $user->password_reset_otp = null;
        $user->password_reset_otp_expiry = null;
        $user->password_reset_otp_is_verified = false;
        $user->save();

        // Step 6: Return success
        return response()->json([
            'success' => true,
            'message' => 'Your password has been successfully reset.',
        ], 200);
    }


    public function resendOtp(Request $request)
    {
        // Simple validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(), // first error only
            ], 422);
        }

        // Find user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Generate a 4-digit OTP
        $otp = $this->otpService->generateOtp($request->email);

        // Save OTP in DB
        $user->password_reset_otp = $otp;
        $user->password_reset_otp_is_verified = false;
        $user->password_reset_otp_expiry = now()->addMinutes($this->otpService->getTtl_min_time());
        $user->save();

        // Send OTP email
        $this->otpService->sendOtpEmail($request->email, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully',
            'data' => [
                'otp_expires_at' => $user->password_reset_otp_expiry,
            ]
        ], 200);
    }

    // public function profileRetrieval(Request $request)
    // {
    //     try {
    //         $user = auth()->user();

    //         // Latest assessment with both score and created_at
    //         $latestAssessment = $user->assessments()->latest()->first();

    //         $latestCreatedAt = $latestAssessment
    //             ? Carbon::parse($latestAssessment->created_at)->format('d F Y, g:i A')
    //             : null;

    //         return jsonResponse(
    //             true,
    //             'User profile retrieved successfully.',
    //             200,
    //             $user->only(['id', 'name', 'email', 'avatar', 'address', 'phone', 'role','is_premium']) + [
    //                 'ossd_score'      => optional($latestAssessment)->score,
    //                 'ossd_created_at' => $latestCreatedAt,
    //             ]
    //         );
    //     } catch (Exception $e) {
    //         return jsonErrorResponse('Failed to retrieve user profile.', 500);
    //     }
    // }

    public function profileUpdate(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'sometimes|nullable|string|max:255',
            'avatar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'user_name' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'bio' => 'sometimes|nullable|string|max:1000',
        ]);

        // Update user table
        if (array_key_exists('name', $validated)) {
            $user->update([
                'name' => $validated['name']
            ]);
        }

        // Get or create profile
        $profile = $user->profile()->firstOrCreate([]);

        // Update profile fields
        $profile->fill([
            'user_name' => $validated['user_name'] ?? $profile->user_name,
            'phone'     => $validated['phone'] ?? $profile->phone,
            'bio'       => $validated['bio'] ?? $profile->bio,
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {

            if ($profile->avatar && file_exists(public_path($profile->avatar))) {
                unlink(public_path($profile->avatar));
            }

            $path = $request->file('avatar')
                            ->store('profile/avatar', 'public');

            $profile->avatar = 'storage/' . $path;
        }

        $profile->save();

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully',
            'data'    => $user->load('profile')
        ]);
    }

    public function changePassword(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or unauthorized',
            ], 401);
        }

        // Check old password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect',
            ], 400);
        }

        // Save new password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ], 200);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or unauthorized',
            ], 401);
        }

        // Soft delete the user
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
        ], 200);
    }
}
