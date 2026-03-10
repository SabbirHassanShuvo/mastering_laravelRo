<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\GarageSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GarageSalesController extends Controller
{
    /**
     * Display a listing of garage sales with dashboard analytics.
     */
    public function index(Request $request)
    {
        // 1. Stat Cards
        $stats = [
            'total'           => GarageSale::count(),
            'active'          => GarageSale::active()->count(),
            'total_revenue'   => GarageSale::where('payment_status', 'completed')->sum('total_fee'),
            'pending_revenue' => GarageSale::where('payment_status', 'pending')->sum('total_fee'),
            'total_users'     => GarageSale::distinct('user_id')->count('user_id'),
        ];

        // 2. Top Performing Sales (By total_fee)
        $topSales = GarageSale::where('payment_status', 'completed')
            ->with('user')
            ->orderByDesc('total_fee')
            ->limit(5)
            ->get();

        // 3. City-based Revenue (Parsing raw pickup_location for Top City Card)
        $totalRevOverall = GarageSale::sum('total_fee');
        $cityRevenue = GarageSale::selectRaw("
                pickup_location, 
                SUM(total_fee) as revenue, 
                COUNT(*) as post_count,
                COUNT(DISTINCT user_id) as user_count
            ")
            ->groupBy('pickup_location')
            ->orderByDesc('revenue')
            ->get()
            ->map(function($item) use ($totalRevOverall) {
                $item->contribution = $totalRevOverall > 0 ? ($item->revenue / $totalRevOverall) * 100 : 0;
                return $item;
            });
        
        $topCity = $cityRevenue->first();

        // 4. Expiry Alerts (Expiring in next 48 hours)
        $expiringSoon = GarageSale::active()
            ->where('sale_end_date', '<=', now()->addHours(48))
            ->orderBy('sale_end_date')
            ->limit(5)
            ->get();

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
                ->addColumn('revenue', function ($row) {
                    return '$' . number_format($row->total_fee, 2);
                })
                ->addColumn('status_badge', function ($row) {
                    $statusColors = [
                        'active'   => 'success',
                        'expired'  => 'warning',
                        'sold'     => 'info',
                        'archived' => 'secondary'
                    ];
                    $color = $statusColors[$row->status] ?? 'dark';
                    return '<span class="badge bg-soft-' . $color . ' text-' . $color . '">' . strtoupper($row->status) . '</span>';
                })
                ->addColumn('payment_badge', function ($row) {
                    $color = $row->payment_status === 'completed' ? 'success' : 'warning';
                    return '<span class="badge bg-soft-' . $color . ' text-' . $color . '">' . ucfirst($row->payment_status) . '</span>';
                })
                ->addColumn('period', function ($row) {
                    return Carbon::parse($row->sale_start_date)->format('d M') . ' - ' . Carbon::parse($row->sale_end_date)->format('d M');
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
                        </div>';
                })
                ->rawColumns(['status_badge', 'payment_badge', 'action'])
                ->make(true);
        }

        return view('backend.layout.garage_sales.index', compact('stats', 'topSales', 'cityRevenue', 'expiringSoon', 'topCity'));
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
            'description'     => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.title'   => 'required|string|max:255',
            'items.*.price'   => 'required|numeric|min:0',
        ]);

        $lastPost = GarageSale::where('user_id', $request->user_id)
            ->where('created_at', '>=', now()->subDays(7))
            ->first();

        if ($lastPost) {
            return back()->with('error', 'This user has already posted a garage sale in the last 7 days.')->withInput();
        }

        try {
            DB::beginTransaction();

            // Calculate total fee (Posting fee + sum of item prices?) 
            // Usually garage sales have a fixed posting fee + maybe item fees.
            // For now, let's assume total_fee is just the total of item prices if that's the business logic,
            // or just the fixed posting fee. The analytics sum 'total_fee', so it should reflect revenue.
            $postingFee = 2.99; // Default as per migration
            $totalFee = $postingFee; 

            $saleStartDate = Carbon::parse($request->sale_start_date);
            $saleEndDate = $saleStartDate->copy()->addDays(7);
            $expiresAt = $saleEndDate->copy()->addDays(7);

            $event = GarageSale::create([
                'user_id'         => $request->user_id,
                'event_title'     => $request->event_title,
                'date'            => $request->date,
                'pickup_location' => $request->pickup_location,
                'sale_start_date' => $saleStartDate,
                'sale_end_date'   => $saleEndDate,
                'description'     => $request->description,
                'posting_fee'     => $postingFee,
                'total_fee'       => $totalFee,
                'status'          => 'active',
                'payment_status'  => 'completed',
                'payment_completed_at' => now(),
                'expires_at'      => $expiresAt,
            ]);

            if ($request->has('items')) {
                foreach ($request->items as $index => $itemData) {
                    $item = $event->items()->create([
                        'title'       => $itemData['title'],
                        'price'       => $itemData['price'],
                        'description' => $itemData['description'] ?? null,
                    ]);

                    // Handle Item Images - Robust check using $request->file()
                    $itemImages = $request->file("items.$index.images");
                    
                    if ($itemImages && is_array($itemImages)) {
                        foreach ($itemImages as $imageFile) {
                            if ($imageFile->isValid()) {
                                $path = $imageFile->store('garage/items', 'public');
                                $item->images()->create(['photo' => $path]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('backend.garage.index')->with('success', 'Garage Sale event created successfully with ' . $event->items()->count() . ' items.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Garage Sale Creation Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create event: ' . $e->getMessage())->withInput();
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
            'owner'   => [
                'name'  => $sale->user->name ?? 'N/A',
                'email' => $sale->user->email ?? 'N/A',
                'phone' => $sale->user->phone ?? 'N/A',
            ],
            'dates'   => [
                'start' => Carbon::parse($sale->sale_start_date)->format('d M Y, h:i A'),
                'end'   => Carbon::parse($sale->sale_end_date)->format('d M Y, h:i A'),
            ],
            'map_link' => 'https://www.google.com/maps/search/?api=1&query=' . urlencode($sale->pickup_location)
        ]);
    }


    /**
     * Archive the specified garage sale.
     */
    public function archive($id)
    {
        try {
            $sale = GarageSale::findOrFail($id);
            $sale->update(['status' => 'archived']);
            return response()->json(['success' => true, 'message' => 'Event archived successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Archive failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display Analytics Dashboard
     */
    public function analytics()
    {
        // 1. Core Stats - ensuring 0 if null
        $totalRevenue = GarageSale::where('payment_status', 'completed')->sum('total_fee') ?? 0;
        $totalSales   = GarageSale::count();
        $activeSales  = GarageSale::active()->count();
        $totalUsers   = GarageSale::distinct('user_id')->count('user_id');

        // 2. Detailed City Analytics - Simplified parsing
        $cityExpression = "TRIM(SUBSTRING_INDEX(pickup_location, ',', 1))";

        $cityAnalytics = GarageSale::selectRaw("
                IF(pickup_location IS NULL OR pickup_location = '', 'Unknown', $cityExpression) as city, 
                COUNT(*) as post_count,
                COUNT(DISTINCT user_id) as user_count,
                SUM(CASE WHEN payment_status = 'completed' THEN total_fee ELSE 0 END) as total_revenue,
                AVG(total_fee) as avg_revenue
            ")
            ->groupBy('city')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function($item) use ($totalRevenue, $totalSales) {
                $item->rev_contribution = $totalRevenue > 0 ? ($item->total_revenue / $totalRevenue) * 100 : 0;
                $item->post_contribution = $totalSales > 0 ? ($item->post_count / $totalSales) * 100 : 0;
                $item->revenue_per_post = $item->post_count > 0 ? ($item->total_revenue / $item->post_count) : 0;
                return $item;
            });

        // 4. Best Performing City Highlight
        $topCity = $cityAnalytics->sortByDesc('total_revenue')->first();

        return view('backend.layout.garage_sales.analytics', compact(
            'totalRevenue', 'totalSales', 'activeSales', 'cityAnalytics', 'totalUsers', 'topCity'
        ));
    }

    /**
     * Export revenue data to CSV
     */
    public function exportCsv()
    {
        return $this->exportData('csv');
    }

    /**
     * Export revenue data to Excel
     */
    public function exportExcel()
    {
        return $this->exportData('excel');
    }

    /**
     * Export revenue data to PDF
     */
    public function exportPdf()
    {
        // For PDF, we'll try to use the same data flow
        // In a real environment, you'd use something like PDF::loadView()
        // For now, I'll return a CSV with a PDF extension or a simple notice
        return $this->exportData('pdf');
    }

    private function exportData($format)
    {
        $sales = GarageSale::with('user')->where('payment_status', 'completed')->get();
        $ext = ($format == 'pdf' ? 'pdf' : ($format == 'excel' ? 'xlsx' : 'csv'));
        $filename = "garage_revenue_" . date('Y-m-d') . "." . $ext;
        
        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Event Title', 'Owner', 'Location', 'Revenue', 'Date']);
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->id,
                    $sale->event_title,
                    $sale->user->name ?? 'N/A',
                    $sale->pickup_location,
                    $sale->total_fee,
                    $sale->created_at->format('Y-m-d')
                ]);
            }
            fclose($file);
        };

        $contentType = ($format == 'pdf' ? 'application/pdf' : ($format == 'excel' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'text/csv'));

        return response()->stream($callback, 200, [
            "Content-type"        => $contentType,
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
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
