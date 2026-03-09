<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\GarageSale;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class GarageSalesController extends Controller
{
    /**
     * Display a listing of garage sales.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = GarageSale::with(['user'])->select('garage_sales.*');

            // Filters
            if ($request->filled('status')) {
                $data->where('status', $request->status);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('owner', function($row){
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('items_count', function($row){
                    return $row->items()->count();
                })
                ->editColumn('date', function($row){
                    return Carbon::parse($row->date)->format('d M Y');
                })
                ->addColumn('status', function($row){
                    $badges = [
                        'active'   => 'success',
                        'expired'  => 'warning',
                        'sold'     => 'info',
                        'archived' => 'secondary'
                    ];
                    $color = $badges[$row->status] ?? 'dark';
                    return '<span class="badge bg-soft-'.$color.' text-'.$color.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex gap-2">
                            <button onclick="viewGarageSale('.$row->id.')" class="btn btn-soft-primary btn-sm" title="View Details">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button onclick="showDeleteConfirm('.$row->id.')" class="btn btn-soft-danger btn-sm" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $stats = [
            'total'    => GarageSale::count(),
            'live'     => GarageSale::where('status', 'active')->where('sale_start_date', '<=', now())->where('sale_end_date', '>=', now())->count(),
            'upcoming' => GarageSale::where('sale_start_date', '>', now())->count(),
            'expired'  => GarageSale::where('status', 'expired')->orWhere('sale_end_date', '<', now())->count(),
        ];

        return view('backend.layout.garage_sales.index', compact('stats'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $users = \App\Models\User::orderBy('name')->get();
        return view('backend.layout.garage_sales.create', compact('users'));
    }

    /**
     * Store a new garage sale event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'event_title'     => 'required|string|max:255',
            'date'            => 'required|date',
            'pickup_location' => 'required|string',
            'sale_start_date' => 'required|date',
            'sale_end_date'   => 'required|date|after:sale_start_date',
            'description'     => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.title'   => 'required|string|max:255',
            'items.*.price'   => 'required|numeric|min:0',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $event = GarageSale::create([
                'user_id'         => $request->user_id,
                'event_title'     => $request->event_title,
                'date'            => $request->date,
                'pickup_location' => $request->pickup_location,
                'sale_start_date' => $request->sale_start_date,
                'sale_end_date'   => $request->sale_end_date,
                'description'     => $request->description,
                'status'          => 'active',
                'payment_status'  => 'completed', // Admin created are assumed completed
            ]);

            foreach ($request->items as $itemData) {
                $event->items()->create([
                    'title'       => $itemData['title'],
                    'price'       => $itemData['price'],
                    'description' => $itemData['description'] ?? null,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('backend.garage.index')->with('success', 'Garage Sale event created successfully.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified garage sale.
     */
    public function show($id)
    {
        $sale = GarageSale::with(['user', 'items.images'])->find($id);
        
        if (!$sale) {
            return response()->json(['success' => false, 'message' => 'Event not found.']);
        }

        return response()->json([
            'success' => true,
            'data'    => $sale,
            'owner'   => $sale->user->name ?? 'N/A',
            'dates'   => [
                'start' => Carbon::parse($sale->sale_start_date)->format('d M Y, h:i A'),
                'end'   => Carbon::parse($sale->sale_end_date)->format('d M Y, h:i A'),
            ]
        ]);
    }

    /**
     * Remove the specified garage sale.
     */
    public function destroy($id)
    {
        try {
            $sale = GarageSale::findOrFail($id);
            $sale->delete();

            return response()->json(['success' => true, 'message' => 'Garage Sale deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting event.']);
        }
    }
}
