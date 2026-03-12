<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\ArchivedProduct;
use App\Models\Category;
use App\Models\Matche;
use App\Models\Product;
use App\Models\ProductLove;
use App\Models\SavedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    // Store product (7-day active, handle multiple photos)
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Total lifetime posts
        $totalPosts = Product::where('user_id', $user->id)->count();
        if ($totalPosts >= 20) {
            return response()->json([
                'message' => 'You have reached the maximum limit of 20 posts in total.'
            ], 403);
        }

        // Posts in last 24 hours
        $last24HoursPosts = Product::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($last24HoursPosts >= 5) {
            return response()->json([
                'message' => 'You can post maximum 5 times in 24 hours.'
            ], 403);
        }

        // Validation
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'product_type' => 'required|in:paid,free,garage_sale',
            'price' => 'nullable|numeric|min:0',
            'condition_status' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'product_image' => 'nullable|image|max:2048',
            'pickup_location' => 'nullable|string',
            'pickup_latitude' => 'nullable|numeric',
            'pickup_longitude' => 'nullable|numeric',
            'pickup_notes' => 'nullable|string',
            'is_urgent' => 'nullable|boolean',
        ]);

        // Expiry logic
        if ($validated['product_type'] === 'paid') {
            $expiresAt = now()->addDays(7);
        } elseif ($validated['product_type'] === 'free') {
            $expiresAt = now()->addHours(rand(48, 72));
        } else {
            $expiresAt = null;
        }

        $product = new Product();
        $product->user_id = $user->id;
        $product->category_id = $validated['category_id'];
        $product->title = $validated['title'];
        $product->product_type = $validated['product_type'];
        $product->price = $validated['price'] ?? null;
        $product->condition_status = $validated['condition_status'] ?? null;
        $product->description = $validated['description'] ?? null;
        $product->pickup_location = $validated['pickup_location'] ?? null;
        $product->pickup_latitude = $validated['pickup_latitude'] ?? null;
        $product->pickup_longitude = $validated['pickup_longitude'] ?? null;
        $product->pickup_notes = $validated['pickup_notes'] ?? null;
        $product->status = Product::STATUS_ACTIVE;
        $product->posted_at = now();
        $product->expires_at = $expiresAt;

        $product->is_urgent = $validated['is_urgent'] ?? false;
        $product->urgent_pickup_date = $product->is_urgent ? now()->toDateString() : null;
        $product->urgent_pickup_notes = $request->input('urgent_pickup_notes');

        if ($request->hasFile('product_image')) {
            $product->product_image = $request->file('product_image')->store('products', 'public');
        }

        $product->save();

        // Multiple Photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $product->photos()->create([
                    'photo_url' => $path,
                    'uploaded_at' => now()
                ]);
            }
        }

        return response()->json(
            $product->load('user', 'category', 'photos'),
            201
        );
    }

    // Edit product (fetch single product)
    public function edit($id)
    {
        $product = Product::with('photos','category')->findOrFail($id);

        return response()->json($product);
    }

    // Update product professionally
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'product_type' => 'required|in:paid,free,garage_sale',
            'price' => 'nullable|numeric|min:0',
            'condition_status' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'product_image' => 'nullable|image|max:2048',
            'pickup_location' => 'nullable|string',
            'pickup_latitude' => 'nullable|numeric',
            'pickup_longitude' => 'nullable|numeric',
            "pickup_notes" => 'nullable|string',
            'is_urgent' => 'nullable|boolean',
            'urgent_pickup_notes' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $product = Product::with('photos')->findOrFail($id);

        // Basic fields update
        $product->category_id = $validated['category_id'];
        $product->title = $validated['title'];
        $product->product_type = $validated['product_type'];
        $product->price = $validated['price'] ?? $product->price;
        $product->condition_status = $validated['condition_status'] ?? $product->condition_status;
        $product->description = $validated['description'] ?? $product->description;
        $product->pickup_location = $validated['pickup_location'] ?? $product->pickup_location;
        $product->pickup_latitude = $validated['pickup_latitude'] ?? $product->pickup_latitude;
        $product->pickup_longitude = $validated['pickup_longitude'] ?? $product->pickup_longitude;
        $product->pickup_notes = $validated['pickup_notes'] ?? $product->pickup_notes;

        // Urgent / Same-Day Pickup
        $product->is_urgent = $validated['is_urgent'] ?? $product->is_urgent;
        $product->urgent_pickup_date = $product->is_urgent ? now()->toDateString() : null;
        $product->urgent_pickup_notes = $validated['urgent_pickup_notes'] ?? $product->urgent_pickup_notes;

        // Main image replacement
        if ($request->hasFile('product_image')) {
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }
            $product->product_image = $request->file('product_image')->store('products', 'public');
        }

        // Multiple photos replacement
        if ($request->hasFile('photos')) {
            foreach ($product->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo_url)) {
                    Storage::disk('public')->delete($photo->photo_url);
                }
                $photo->delete();
            }

            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $product->photos()->create([
                    'photo_url' => $path,
                    'uploaded_at' => now()
                ]);
            }
        }

        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully!',
            'data' => $product->load('user', 'category', 'photos')
        ], 200);
    }

    // High-level product search
    public function search(Request $request)
    {
        $query = Product::with('category','photos','user');

        // 1. Title search
        if ($request->filled('title')) {
            $query->where('title', 'like', '%'.$request->title.'%');
        }

        // 2. Description search
        if ($request->filled('description')) {
            $query->where('description', 'like', '%'.$request->description.'%');
        }

        // 3. Product type
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        // 4. Pickup location
        if ($request->filled('pickup_location')) {
            $query->where('pickup_location', 'like', '%'.$request->pickup_location.'%');
        }

        // 5. Category title (fixed)
        if ($request->filled('category_title')) {
            $query->whereHas('category', function($q) use ($request){
                $q->where('title', 'like', '%'.$request->category_title.'%'); // <-- name → title
            });
        }

        // Sorting newest first
         $products= $query->orderBy('posted_at', 'desc')->get();

        // Pagination
      

        return response()->json([
            'status' => 'success',
            'data' => $products,
            'code' => 200,
            'message' => 'Products retrieved successfully'
        ]);
    }

    // Archive product
    public function archive(Product $product)
    {
        ArchivedProduct::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product archived successfully'
        ]);
    }

    public function unarchive(Product $product)
    {
        ArchivedProduct::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product unarchived successfully'
        ]);
    }

    public function myArchivedProducts()
    {
        $archives = ArchivedProduct::where('user_id', auth()->id())
            ->with(['product' => function ($query) {
                $query->withCount('loves');
            }])
            ->latest()
            ->get()
            ->map(function ($archive) {

                if (!$archive->product) {
                    return null;
                }

                $product = $archive->product;

                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'product_image' => $product->product_image,
                    'status' => $product->status,
                    'interest_count' => $product->loves_count
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'status' => true,
            'data' => $archives
        ]);
    }

    public function archivedProductDetails($id)
    {
        $archive = ArchivedProduct::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->with(['product.photos', 'product.category'])
            ->firstOrFail();

        $product = $archive->product;

        return response()->json([
            'status' => true,
            'data' => [
                'photos' => $product->photos->pluck('photo_url'), 
                'title' => $product->title,
                'price' => $product->price,
                'status' => $product->status,
                'created_at' => $product->created_at->toDateTimeString(),
                'condition' => $product->condition_status,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name
                ],
                'interest_count' => $product->loves()->count(),
                'description' => $product->description,
                'latitude' => $product->pickup_latitude,
                'longitude' => $product->pickup_longitude,
            ]
        ]);
    }

    // listing all products
    // public function index()
    // {
    //     $products = Product::with('photos','user','category')->latest()->get();
    //     return response()->json($products);
    // }

    // Relist expired product (resets 7-day cycle)
    public function relist($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.'
            ], 404);
        }

        if ($product->status !== Product::STATUS_EXPIRED) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only expired products can be relisted.'
            ], 400);
        }

        $product->status = Product::STATUS_ACTIVE;
        $product->expires_at = now()->addDays(7); // new 7-day cycle
        $product->notified_before_expiry = false; // reset notifications
        $product->notified_before_delete = false;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product relisted successfully.',
            'data' => $product
        ], 200);
    }

    // List products by status
    public function productsByStatus(Request $request){
         $validStatuses = [
            Product::STATUS_ACTIVE,
            Product::STATUS_EXPIRED,
            Product::STATUS_SOLD
        ];

        if (!in_array($request->status, $validStatuses)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid status'
            ], 400);
        }

        $products = Product::where('status', $request->status)
            ->withCount('loves') // interest count
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'product_image' => $product->product_image,
                    'status' => $product->status,
                    'interest_count' => $product->loves_count
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    public function productDetails($id)
    {
        $product = Product::with(['photos', 'category'])
            ->withCount('loves') // interest count
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => [
                'photos' => $product->photos->pluck('image'), // শুধু image URLs
                'title' => $product->title,
                'price' => $product->price,
                'status' => $product->status,
                'created_at' => $product->created_at->toDateTimeString(),
                'condition' => $product->condition,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name
                ],
                'interest_count' => $product->loves_count,
                'description' => $product->description,
                'latitude' => $product->latitude,
                'longitude' => $product->longitude,
            ]
        ]);
    }

    // Like / Unlike a product
    // public function toggle(Request $request, $productId)
    // {
    //     $user = $request->user();
    //     if (!$user) return response()->json(['status'=>false, 'message'=>'Unauthorized'],401);

    //     $product = Product::findOrFail($productId);

    //     $existing = ProductLove::where('product_id',$product->id)
    //                             ->where('user_id',$user->id)
    //                             ->first();

    //     if($existing){
    //         $existing->delete();
    //         $status = 'unliked';
    //     } else {
    //         ProductLove::create([
    //             'product_id' => $product->id,
    //             'user_id' => $user->id
    //         ]);
    //         $status = 'liked';
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => "Product {$status} successfully",
    //         'total_loves' => $product->loves()->count()
    //     ]);
    // }

    // Get all users who loved a product
    // public function allLoves($productId)
    // {
    //     $product = Product::with('lovedUsers:id,name,email')->findOrFail($productId);

    //     return response()->json([
    //         'status' => true,
    //         'total_loves' => $product->loves()->count(),
    //         'data' => $product->lovedUsers
    //     ]);
    // }
    
    // Filter Product use Multi data
    public function filterProducts(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->latitude || !$user->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'User location not found'
            ], 400);
        }

        $lat = $user->latitude;
        $lng = $user->longitude;

        $query = Product::query()
            ->selectRaw("products.*, 
                (6371 * acos(
                    LEAST(1.0,
                        cos(radians(?)) * cos(radians(pickup_latitude)) 
                        * cos(radians(pickup_longitude) - radians(?)) 
                        + sin(radians(?)) * sin(radians(pickup_latitude))
                    )
                )) AS distance", [$lat, $lng, $lat])
            ->with(['photos:id,product_id,photo_url', 'category:id,title'])
            ->where('status', 'active')
            ->whereNotNull('pickup_latitude')
            ->whereNotNull('pickup_longitude');

        // Distance filter
        if ($request->filled('distance')) {
            $query->having('distance', '<=', $request->distance);
        }

        // Price filter
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $min = $request->price_min ?? 0;
            $max = $request->price_max ?? 999999999;
            $query->whereBetween('price', [$min, $max]);
        }

        // Category filter
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Sale type filter (multi-option support)
        if ($request->filled('sale_type')) {
            $types = is_array($request->sale_type) ? $request->sale_type : [$request->sale_type];

            $query->where(function($q) use ($types) {
                foreach ($types as $type) {
                    if ($type === 'urgent') $q->orWhere('is_urgent', true);
                    if ($type === 'today') $q->orWhereDate('posted_at', now()->toDateString());
                    if ($type === 'week') $q->orWhereBetween('posted_at', [now()->startOfWeek(), now()->endOfWeek()]);
                }
            });
        }

        // Ordering
        $query->orderByDesc('is_spotlighted')
              ->orderByDesc('is_urgent')
              ->orderBy('distance');

        $products = $query->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // Get all categories with product counts
    public function categories()
    {
        $categories = Category::where('status', true)
            ->latest()
            ->select('id', 'title', 'slug', 'image')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);

    }

    public function toggle(Request $request, $productId)
    {
        $user = $request->user();

        $product = Product::with(['user.profile'])->findOrFail($productId);

        // Like
        ProductLove::firstOrCreate([
            'product_id' => $product->id,
            'user_id' => $user->id
        ]);

        // Match 
        $match = Matche::firstOrCreate([
            'product_id' => $product->id,
            'user_one_id' => $product->user_id,
            'user_two_id' => $user->id
        ]);

        return response()->json([
            'status' => true,
            'type' => 'like',
            'data' => [
                'product' => [
                    'id' => $product->id,
                    'image' => $product->image,
                    'price' => $product->price,
                ],
                'owner' => [
                    'id' => $product->user->id,
                    'name' => $product->user->name,
                    'avatar' => optional($product->user->profile)->avatar,
                    'image' => optional($product->user->profile)->image,
                ],
                'liked_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => optional($user->profile)->avatar,
                    'image' => optional($user->profile)->image,
                ]
            ]
        ]);
    }

    public function productInterestUsers($productId)
    {
        $product = Product::findOrFail($productId);

        // $currentUser = auth()->user();
        // if ($product->user_id !== $currentUser->id) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }

        $interestedUsers = ProductLove::where('product_id', $product->id)
            ->with('user.profile')
            ->get()
            ->map(function ($love) {
                return [
                    'id' => $love->user->id,
                    'name' => $love->user->name,
                    'avatar' => optional($love->user->profile)->avatar,
                    'image' => optional($love->user->profile)->image,
                    'liked_at' => $love->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'status' => true,
            'product' => [
                'id' => $product->id,
                'title' => $product->title
            ],
            'interested_users' => $interestedUsers
        ]);
    }

    // Save or toggle a product
    public function toggleSave(Request $request, $productId)
    {
        $user = $request->user();

        $saved = SavedProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($saved) {
            $saved->delete();
            return response()->json([
                'status' => true,
                'message' => 'Product removed from saved list'
            ]);
        }

        SavedProduct::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product saved successfully'
        ]);
    }

    // Fetch all saved products
    public function fetchSavedProducts(Request $request)
    {
        $user = $request->user();

        $savedProducts = SavedProduct::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->product->id,
                    'title' => $item->product->title,
                    'price' => $item->product->price,
                    'product_image' => $item->product->product_image,
                    'status' => 'saved'
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $savedProducts
        ]);
    }
}
