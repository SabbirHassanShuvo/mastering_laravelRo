<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{

    // List all products
    public function index()
    {
        $products = Product::with(['photos', 'user', 'category'])
                    ->latest()
                    ->get();

        return response()->json($products);
    }


    // Product Store
    public function store(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Logged-in user ID assign
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

        // Main Product Image
        if ($request->hasFile('product_image')) {
            $product->product_image = $request->file('product_image')->store('products', 'public');
        }

        $product->save(); // Save product

        // Multiple photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $product->photos()->create([
                    'photo_url' => $path,
                    'uploaded_at' => now(),
                ]);
            }
        }

        return response()->json($product->load('user', 'category', 'photos'), 201);
    }
}
