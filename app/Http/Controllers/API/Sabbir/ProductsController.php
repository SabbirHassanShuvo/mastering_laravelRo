<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    // Store product (7-day active, handle multiple photos)
    public function store(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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
            'pickup_longitude' => 'nullable|numeric'
        ]);

        // Expiry Logic
        if ($validated['product_type'] === 'paid') {
            $expiresAt = now()->addDays(7);
        } elseif ($validated['product_type'] === 'free') {
            $expiresAt = now()->addHours(rand(48, 72));
        } else {
            $expiresAt = null;
        }


        // Expiry Logic for testing (1 minute)
        // if ($validated['product_type'] === 'paid') {
        //     $expiresAt = now()->addMinutes(3); // paid products expire in 1 minute
        // } elseif ($validated['product_type'] === 'free') {
        //     $expiresAt = now()->addSeconds(rand(30, 60)); // free products expire in 30–60 seconds
        // } else {
        //     $expiresAt = null; // garage_sale or others don't expire
        // }

        $product = new Product();
        $product->user_id = auth()->id();
        $product->category_id = $validated['category_id'];
        $product->title = $validated['title'];
        $product->product_type = $validated['product_type'];
        $product->price = $validated['price'] ?? null;
        $product->condition_status = $validated['condition_status'] ?? null;
        $product->description = $validated['description'] ?? null;
        $product->pickup_location = $validated['pickup_location'] ?? null;
        $product->pickup_latitude = $validated['pickup_latitude'] ?? null;
        $product->pickup_longitude = $validated['pickup_longitude'] ?? null;
        $product->status = Product::STATUS_ACTIVE;
        $product->posted_at = now();
        $product->expires_at = $expiresAt;

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
   // Update product with full image management
    public function update(Request $request, $id)
    {
        $product = Product::with('photos')->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.'
            ], 404);
        }

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
            'photos.*' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Update basic fields
        $product->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'product_type' => $validated['product_type'],
            'price' => $validated['price'] ?? null,
            'condition_status' => $validated['condition_status'] ?? null,
            'description' => $validated['description'] ?? null,
            'pickup_location' => $validated['pickup_location'] ?? null,
            'pickup_latitude' => $validated['pickup_latitude'] ?? null,
            'pickup_longitude' => $validated['pickup_longitude'] ?? null,
        ]);

        // Handle main image replacement
        if ($request->hasFile('product_image')) {
            // Delete old main image from storage and database
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }
            $product->product_image = $request->file('product_image')->store('products', 'public');
            $product->save();
        }

        // Handle multiple photos replacement
        if ($request->hasFile('photos')) {
            // Delete old multiple photos from storage and database
            foreach ($product->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo_url)) {
                    Storage::disk('public')->delete($photo->photo_url);
                }
                $photo->delete();
            }

            // Store new uploaded photos
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $product->photos()->create([
                    'photo_url' => $path,
                    'uploaded_at' => now()
                ]);
            }
        }

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
    public function archive(Product $product){
        $product->status = Product::STATUS_ARCHIVED;
        $product->save();
        return response()->json(['message'=>'Product archived successfully']);
    }

    // Unarchive product (resets 7-day cycle)
    public function unarchive(Product $product){
        $product->status = Product::STATUS_ACTIVE;
        $product->expires_at = now()->addDays(7);
        $product->save();
        return response()->json(['message'=>'Product unarchived and active']);
    }

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
        $query = Product::query();
        if($request->status === 'active') $query->where('status', Product::STATUS_ACTIVE);
        elseif($request->status === 'expired') $query->where('status', Product::STATUS_EXPIRED);
        elseif($request->status === 'archived') $query->where('status', Product::STATUS_ARCHIVED);
        else return response()->json(['message'=>'Invalid status'], 400);

        $products = $query->with('photos','user','category')->get();
        return response()->json($products);
    }

}
