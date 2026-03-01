<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\GarageArchived;
use App\Models\GarageItem;
use App\Models\GarageLove;
use App\Models\GarageSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GarageSalesController extends Controller
{
    // public function store(Request $request)
    // {
    //     // Simple validation
    //     $validator = Validator::make($request->all(), [
    //         'event_title' => 'required|string|max:255',
    //         'date' => 'required|date',
    //         'pickup_location' => 'required|string|max:255',
    //         'sale_start_date' => 'required|date',
    //         'sale_end_date' => 'required|date|after_or_equal:sale_start_date',
    //         'latitude' => 'nullable|numeric',
    //         'longitude' => 'nullable|numeric',
    //         'items' => 'required|array|min:1',
    //         'items.*.title' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     // Garage Sale Insert (new + save)
    //     $garage = new GarageSale();
    //     $garage->user_id = auth()->id();
    //     $garage->event_title = $request->event_title;
    //     $garage->description = $request->description ?? null;
    //     $garage->date = $request->date;
    //     $garage->pickup_location = $request->pickup_location;
    //     $garage->sale_start_date = $request->sale_start_date;
    //     $garage->sale_end_date = $request->sale_end_date;
    //     $garage->expires_at = Carbon::now()->addDays(7);

    //     $garage->latitude = $request->latitude ?? null;
    //     $garage->longitude = $request->longitude ?? null;

    //     // testing dates (1 min expiry)
    //     // $garage->sale_start_date = now();
    //     // $garage->sale_end_date = Carbon::now()->addMinutes(1); // expire in 1 min
    //     // $garage->expires_at = Carbon::now()->addMinutes(1);    // 1 min expiry
        
    //     $garage->posting_fee = $request->posting_fee ?? 2.99; // hardcoded default value
    //     $garage->total_fee = $request->total_fee ?? 0; // hardcoded default value
    //     $garage->status = $request->status ?? 'active'; // hardcoded default value
    //     $garage->save();

    //     // Garage Items + Images
    //     foreach ($request->items as $itemData) {
    //         $item = $garage->items()->create([
    //             'title' => $itemData['title'],
    //             'price' => $itemData['price'] ?? null,
    //             'description' => $itemData['description'] ?? null
    //         ]);

    //         if (!empty($itemData['images'])) {
    //             foreach ($itemData['images'] as $file) {
    //                 if (is_file($file)) { 
    //                     $path = $file->store('garage_items', 'public');
    //                     $item->images()->create(['photo' => $path]);
    //                 } else {
    //                     $item->images()->create(['photo' => $file]);
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Garage Sale Published successfully',
    //         'garage' => $garage->load('items.images')
    //     ]);
    // }
    public function store(Request $request)
    {
        // Simple validation
        $validator = Validator::make($request->all(), [
            'event_title' => 'required|string|max:255',
            'date' => 'required|date',
            'pickup_location' => 'required|string|max:255',
            'sale_start_date' => 'required|date',
            'sale_end_date' => 'required|date|after_or_equal:sale_start_date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Garage Sale Insert (new + save)
        $garage = new GarageSale();
        $garage->user_id = auth()->id();
        $garage->event_title = $request->event_title;
        $garage->description = $request->description ?? null;
        $garage->date = $request->date;
        $garage->pickup_location = $request->pickup_location;
        $garage->sale_start_date = $request->sale_start_date;
        $garage->sale_end_date = $request->sale_end_date;
        $garage->expires_at = Carbon::now()->addDays(7);

        $garage->latitude = $request->latitude ?? null;
        $garage->longitude = $request->longitude ?? null;

        // testing dates (1 min expiry)
        // $garage->sale_start_date = now();
        // $garage->sale_end_date = Carbon::now()->addMinutes(1); // expire in 1 min
        // $garage->expires_at = Carbon::now()->addMinutes(1);    // 1 min expiry
        
        $garage->posting_fee = $request->posting_fee ?? 2.99; // hardcoded default value
        $garage->total_fee = $request->total_fee ?? 0; // hardcoded default value
        $garage->status = $request->status ?? 'active'; // hardcoded default value
        $garage->save();

        // Garage Items + Images
        foreach ($request->items as $itemData) {
            $item = $garage->items()->create([
                'title' => $itemData['title'],
                'price' => $itemData['price'] ?? null,
                'description' => $itemData['description'] ?? null
            ]);

            if (!empty($itemData['images'])) {
                foreach ($itemData['images'] as $file) {
                    if (is_file($file)) { 
                        $path = $file->store('garage_items', 'public');
                        $item->images()->create(['photo' => $path]);
                    } else {
                        $item->images()->create(['photo' => $file]);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Garage Sale Published successfully',
            'garage' => $garage->load('items.images')
        ]);
    }

    public function edit($id)
    {
        $garage = GarageSale::with('items.images')
            ->where('id', $id)
            ->first();

        return response()->json([
            'auth_id' => auth()->id(),
            'garage' => $garage
        ]);
    }

    public function update(Request $request, $id)
    {
        $garage = GarageSale::where('user_id', auth()->id())->find($id);

        if (!$garage) {
            return response()->json([
                'status' => 'error',
                'message' => 'Garage Sale not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'event_title' => 'required|string|max:255',
            'date' => 'required|date',
            'pickup_location' => 'required|string|max:255',
            'sale_start_date' => 'required|date',
            'sale_end_date' => 'required|date|after_or_equal:sale_start_date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // ===== Update Garage =====
        $garage->event_title = $request->event_title;
        $garage->description = $request->description ?? null;
        $garage->date = $request->date;
        $garage->pickup_location = $request->pickup_location;
        $garage->sale_start_date = $request->sale_start_date;
        $garage->sale_end_date = $request->sale_end_date;
        $garage->expires_at = Carbon::parse($request->sale_end_date)->addDays(7);
        $garage->posting_fee = $request->posting_fee ?? $garage->posting_fee;
        $garage->total_fee = $request->total_fee ?? $garage->total_fee;
        $garage->status = $request->status ?? 'active'; // hardcoded default value
        $garage->latitude = $request->latitude ?? $garage->latitude;
        $garage->longitude = $request->longitude ?? $garage->longitude;
        $garage->save();

        // ===== Delete old items & images =====
        foreach ($garage->items as $oldItem) {

            foreach ($oldItem->images as $image) {
                if (Storage::disk('public')->exists($image->photo)) {
                    Storage::disk('public')->delete($image->photo);
                }
                $image->delete();
            }

            $oldItem->delete();
        }

        // ===== Insert new items =====
        foreach ($request->items as $itemData) {

            $item = $garage->items()->create([
                'title' => $itemData['title'],
                'price' => $itemData['price'] ?? null,
                'description' => $itemData['description'] ?? null
            ]);

            if (!empty($itemData['images'])) {
                foreach ($itemData['images'] as $file) {

                    if (is_file($file)) {
                        $path = $file->store('garage_items', 'public');
                        $item->images()->create(['photo' => $path]);
                    } else {
                        $item->images()->create(['photo' => $file]);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Garage Sale updated successfully',
            'garage' => $garage->load('items.images')
        ]);
    }

    // Garage Sale Relist
    public function relist(Request $request, $id)
    {
        $garage = GarageSale::where('user_id', auth()->id())->find($id);

        if (!$garage) {
            return response()->json([
                'status' => 'error',
                'message' => 'Garage Sale not found'
            ], 404);
        }

        // Allow relist only if expired
        if ($garage->status !== 'expired' && $garage->expires_at > Carbon::now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only expired garage sales can be relisted'
            ], 400);
        }

        $now = Carbon::now();

        $garage->sale_start_date = $now;
        $garage->sale_end_date = $now->copy()->addDays(7);
        $garage->expires_at = $now->copy()->addDays(7);
        $garage->status = 'active';

        $garage->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Garage Sale relisted for 1 week successfully',
            'garage' => $garage->load('items.images')
        ]);
    }

    // Archive a garage
    public function archive(Request $request, $garageId)
    {
        $archived = GarageArchived::firstOrCreate([
            'user_id' => auth()->id(),
            'garage_id' => $garageId
        ]);

        return response()->json([
            'message' => 'Garage archived successfully',
            'archived' => $archived
        ]);
    }

    // Unarchive a garage
    public function unarchive($garageId)
    {
        $archived = GarageArchived::where('user_id', auth()->id())
            ->where('garage_id', $garageId)
            ->first();

        if ($archived) {
            $archived->delete();
            return response()->json([
                'message' => 'Garage unarchived successfully'
            ]);
        }

        return response()->json([
            'message' => 'Garage not archived'
        ], 404);
    }

    // Garage Sales by Status
    public function garageByStatus(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:active,expired,sold,archived'
        ]);

        $query = GarageSale::with([
            'items.images'
        ]);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $garageSales = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $garageSales
        ]);
    }

    // My Archived Garage Sales
    public function myArchivedGarages()
    {
        $garages = GarageSale::with('items.images')
            ->whereHas('archivedByUsers', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $garages
        ]);
    }

    // Like / Unlike a garage sale
    public function toggle(Request $request, $garageId)
    {
        $user = $request->user();
        if (!$user) return response()->json(['status'=>false,'message'=>'Unauthorized'],401);

        $garage = GarageSale::findOrFail($garageId);

        $existing = GarageLove::where('garage_id',$garage->id)
                              ->where('user_id',$user->id)
                              ->first();

        if($existing){
            $existing->delete();
            $status = 'unliked';
        } else {
            GarageLove::create([
                'garage_id' => $garage->id,
                'user_id' => $user->id
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => true,
            'message' => "Garage {$status} successfully",
            'total_loves' => $garage->loves()->count()
        ]);
    }

    // Get all users who loved a garage sale
    public function users($garageId)
    {
        $garage = GarageSale::with('lovedUsers:id,name,email')->find($garageId);

        if (!$garage) {
            return response()->json([
                'success' => false,
                'message' => 'Garage Sale not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Users who loved this garage sale fetched successfully.',
            'data' => [
                'total_loves' => $garage->loves()->count(),
                'users' => $garage->lovedUsers
            ]
        ], 200);
    }

    // Garage Item Details
    public function garageItemShow($id)
    {
         $item = GarageItem::with(['garageSale.user', 'images'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

}
