<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\GarageSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    // Fetch products based on user's location
    public function homeProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lat = $request->latitude;
        $lng = $request->longitude;

        $products = Product::selectRaw("
            products.*,
            ROUND(
                6371 * acos(
                    cos(radians(?))
                    * cos(radians(pickup_latitude))
                    * cos(radians(pickup_longitude) - radians(?))
                    + sin(radians(?))
                    * sin(radians(pickup_latitude))
                )
            , 2) AS distance
        ", [$lat, $lng, $lat])
        ->with([
            'photos',
            'user:id',
            'user.profile:id,user_id,user_name,avatar' 
        ])
        ->where('status', 'active')
        ->whereNotNull('pickup_latitude')
        ->whereNotNull('pickup_longitude')
        ->orderBy('distance')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // Product detsails based on product id
    public function productDetail($id)
    {
        $user = auth()->user();

        if (!$user->latitude || !$user->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'User location not found'
            ], 400);
        }

        $lat = $user->latitude;
        $lng = $user->longitude;

        $product = Product::selectRaw("
                products.*,
                (
                    6371 * acos(
                        LEAST(1.0,
                            cos(radians(?))
                            * cos(radians(pickup_latitude))
                            * cos(radians(pickup_longitude) - radians(?))
                            + sin(radians(?))
                            * sin(radians(pickup_latitude))
                        )
                    )
                ) AS distance
            ", [$lat, $lng, $lat])
            ->with([
                'photos',
                'category',
                'user' => function ($query) {
                    $query->select('id', 'name', 'status');
                },
                'user.profile' => function ($query) {
                    $query->select('id', 'user_id', 'avatar', 'user_name');
                }
            ])
            ->where('id', $id)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    // Fetch products based on user's location and category
    public function homeGarageSales(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lat = $request->latitude;
        $lng = $request->longitude;

        $garageSales = GarageSale::selectRaw("
            garage_sales.*,
            ROUND(
                6371 * acos(
                    cos(radians(?))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?))
                    * sin(radians(latitude))
                )
            , 2) AS distance
        ", [$lat, $lng, $lat])
        ->with([
            'items.images', 
            'user:id',
                'user.profile:id,user_id,user_name,avatar'
        ])
        ->where('status', 'active')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->orderByDesc('is_spotlighted')
        ->orderBy('distance')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $garageSales
        ]);
    }
    // Garage sale details based on garage sale id
    public function garageDetail($id)
    {
        $user = auth()->user();

        if (!$user->latitude || !$user->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'User location not found'
            ], 400);
        }

        $lat = $user->latitude;
        $lng = $user->longitude;

        $garage = GarageSale::selectRaw("
                garage_sales.*,
                (
                    6371 * acos(
                        LEAST(1.0,
                            cos(radians(?))
                            * cos(radians(latitude))
                            * cos(radians(longitude) - radians(?))
                            + sin(radians(?))
                            * sin(radians(latitude))
                        )
                    )
                ) AS distance
            ", [$lat, $lng, $lat])
            ->with([
                'items.images', 
                'user:id',
                    'user.profile:id,user_id,user_name,avatar'
            ])
            ->where('id', $id)
            ->first();

        if (!$garage) {
            return response()->json([
                'success' => false,
                'message' => 'Garage not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $garage
        ]);
    }
}
