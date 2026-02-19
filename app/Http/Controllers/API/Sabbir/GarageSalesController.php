<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Http\Controllers\Controller;
use App\Models\GarageSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GarageSalesController extends Controller
{
    public function store(Request $request)
{
    // Simple validation
    $validator = Validator::make($request->all(), [
        'event_title' => 'required|string|max:255',
        'date' => 'required|date',
        'pickup_location' => 'required|string|max:255',
        'sale_start_date' => 'required|date',
        'sale_end_date' => 'required|date|after_or_equal:sale_start_date',
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
    $garage->expires_at = Carbon::parse($request->sale_end_date)->addDays(7);
    $garage->posting_fee = $request->posting_fee ?? 2.99; // hardcoded default value
    $garage->total_fee = $request->total_fee ?? 0; // hardcoded default value
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
        'message' => 'Garage Sale created successfully',
        'garage' => $garage->load('items.images')
    ]);
}


}
