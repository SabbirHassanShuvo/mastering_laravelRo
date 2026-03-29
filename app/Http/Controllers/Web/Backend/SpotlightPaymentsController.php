<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SpotlightPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SpotlightPaymentsController extends Controller
{
    /**
     * Display a listing of spotlight and boost payments.
     */
    public function index(Request $request)
    {
        // 1️⃣ Stat Cards
        $stats = [
            'total_revenue'  => SpotlightPayment::successful()->sum('total_fee'),
            'active_boosts'  => SpotlightPayment::active()->count(),
            'pending_count'  => SpotlightPayment::pending()->count(),
            'failed_count'   => SpotlightPayment::where('spotlight_payments.status', 'failed')->count(),
        ];

        // 2️⃣ Top Boosted Products (By revenue & count)
        $topProducts = SpotlightPayment::successful()
            ->with('product')
            ->selectRaw('spotlight_payments.product_id, SUM(spotlight_payments.total_fee) as total_spent, COUNT(*) as boost_count')
            ->groupBy('product_id')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // 3️⃣ City-based Revenue (Parsing city from pickup_location)
        // Note: This assumes pickup_location is "City, Region, etc."
        $totalRevenueOverall = SpotlightPayment::successful()->sum('total_fee');

        $cityRevenue = Product::selectRaw('
                CASE 
                    WHEN products.pickup_location IS NULL OR TRIM(products.pickup_location) = "" THEN "Unknown"
                    ELSE TRIM(SUBSTRING_INDEX(products.pickup_location, ",", 1))
                END as city, 
                SUM(COALESCE(spotlight_payments.total_fee, 0)) as revenue,
                COUNT(spotlight_payments.id) as boost_count,
                COUNT(DISTINCT products.id) as total_products
            ')
            ->leftJoin('spotlight_payments', function($join) {
                $join->on('products.id', '=', 'spotlight_payments.product_id')
                     ->where('spotlight_payments.status', '=', 'paid');
            })
            ->groupBy('city')
            ->orderByDesc('revenue')
            ->get()
            ->map(function($item) use ($totalRevenueOverall) {
                $item->revenue = (float)$item->revenue;
                $item->boost_count = (int)$item->boost_count;
                $item->total_products = (int)$item->total_products;
                $item->contribution = $totalRevenueOverall > 0 ? ($item->revenue / $totalRevenueOverall) * 100 : 0;
                $item->capture_rate = $item->total_products > 0 ? ($item->boost_count / $item->total_products) * 100 : 0;
                return $item;
            });

        // 4️⃣ Spotlight Expiry Alerts (Expiring in next 24 hours)
        $expiringSoon = SpotlightPayment::active()
            ->where('spotlight_end_at', '<=', now()->addHours(24))
            ->with(['product', 'user'])
            ->get();

        if ($request->ajax()) {
            $query = SpotlightPayment::with(['user', 'product'])
                ->select('spotlight_payments.*');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user', function ($payment) {
                    return $payment->user ? $payment->user->name : 'N/A';
                })
                ->addColumn('product', function ($payment) {
                    return $payment->product ? $payment->product->title : 'N/A';
                })
                ->addColumn('formatted_amount', function ($payment) {
                    return '$' . number_format($payment->total_fee, 2);
                })
                ->addColumn('status_badge', function ($payment) {
                    $colors = [
                        'paid'    => 'success',
                        'pending' => 'warning',
                        'failed'  => 'danger',
                    ];
                    $color = $colors[$payment->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($payment->status) . '</span>';
                })
                ->addColumn('period', function ($payment) {
                    if ($payment->spotlight_start_at && $payment->spotlight_end_at) {
                        return $payment->spotlight_start_at->format('d M') . ' - ' . $payment->spotlight_end_at->format('d M Y');
                    }
                    return 'N/A';
                })
                ->addColumn('created_at', function ($payment) {
                    return $payment->created_at->format('d M Y');
                })
                ->addColumn('action', function ($payment) {
                    return '<button onclick="viewPaymentDetail(' . $payment->id . ')" class="btn btn-soft-info btn-sm">
                                <i class="ri-eye-line"></i>
                            </button>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('backend.layout.spotlight.index', compact('stats', 'topProducts', 'cityRevenue', 'expiringSoon'));
    }

    /**
     * Get payment details via AJAX.
     */
    public function cityAnalytics()
    {
        $totalRevenueOverall = SpotlightPayment::successful()->sum('total_fee');
        
        $cityExpression = 'CASE 
                    WHEN products.pickup_location IS NULL OR TRIM(products.pickup_location) = "" THEN "Unknown"
                    ELSE TRIM(SUBSTRING_INDEX(products.pickup_location, ",", 1))
                END';

        $cityRevenue = Product::selectRaw("
                $cityExpression as city, 
                SUM(COALESCE(spotlight_payments.total_fee, 0)) as revenue,
                COUNT(spotlight_payments.id) as boost_count,
                COUNT(DISTINCT products.id) as total_products,
                COUNT(DISTINCT CASE WHEN spotlight_payments.status = 'paid' THEN products.id END) as boosted_products_count,
                COUNT(DISTINCT CASE WHEN spotlight_payments.status = 'paid' THEN spotlight_payments.user_id END) as unique_users,
                MAX(spotlight_payments.created_at) as last_boost_at
            ")
            ->leftJoin('spotlight_payments', function($join) {
                $join->on('products.id', '=', 'spotlight_payments.product_id')
                     ->where('spotlight_payments.status', '=', 'paid');
            })
            ->groupByRaw($cityExpression)
            ->orderByDesc('revenue')
            ->get()
            ->map(function($item) use ($totalRevenueOverall) {
                $revenue = (float)$item->revenue;
                $boost_count = (int)$item->boost_count;
                $total_products = (int)$item->total_products;
                $boosted_products_count = (int)$item->boosted_products_count;
                $unique_users = (int)$item->unique_users;

                return (object)[
                    'city' => (string)$item->city,
                    'revenue' => $revenue,
                    'boost_count' => $boost_count,
                    'total_products' => $total_products,
                    'boosted_products_count' => $boosted_products_count,
                    'unique_users' => $unique_users,
                    'contribution' => $totalRevenueOverall > 0 ? (float)(($revenue / $totalRevenueOverall) * 100) : 0,
                    'capture_rate' => $total_products > 0 ? (float)(($boosted_products_count / $total_products) * 100) : 0,
                    'avg_boost_value' => $boost_count > 0 ? (float)($revenue / $boost_count) : 0,
                    'last_boost_at' => $item->last_boost_at,
                ];
            });

        return view('backend.layout.spotlight.city_analytics', compact('cityRevenue', 'totalRevenueOverall'));
    }

    public function show($id)
    {
        $payment = SpotlightPayment::with(['user', 'product'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $payment,
            'formatted_amount' => '$' . number_format($payment->total_fee, 2),
            'user_name'        => $payment->user->name ?? 'N/A',
            'product_title'    => $payment->product->title ?? 'N/A',
            'dates'            => [
                'start' => $payment->spotlight_start_at ? $payment->spotlight_start_at->format('d M Y, h:i A') : 'N/A',
                'end'   => $payment->spotlight_end_at ? $payment->spotlight_end_at->format('d M Y, h:i A') : 'N/A',
                'pay'   => $payment->created_at->format('d M Y, h:i A'),
            ]
        ]);
    }

    /**
     * Export all payment logs to CSV (Excel compatible) for accounting.
     */
    public function exportCsv()
    {
        $fileName = 'spotlight_payments_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Transaction ID', 'User Name', 'User Email', 'Product Title', 'Product ID', 'Plan', 'Currency', 'Amount', 'Status', 'Start Date', 'End Date']);

            SpotlightPayment::with(['user', 'product'])->orderByDesc('created_at')->chunk(100, function($payments) use ($file) {
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->created_at->format('Y-m-d H:i:s'),
                        $payment->stripe_payment_intent_id,
                        $payment->user->name ?? 'N/A',
                        $payment->user->email ?? 'N/A',
                        $payment->product->title ?? 'N/A',
                        $payment->product_id,
                        $payment->boost_plan,
                        strtoupper($payment->currency),
                        $payment->total_fee,
                        ucfirst($payment->status),
                        $payment->spotlight_start_at ? $payment->spotlight_start_at->format('Y-m-d') : 'N/A',
                        $payment->spotlight_end_at ? $payment->spotlight_end_at->format('Y-m-d') : 'N/A',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export payment logs to PDF.
     */
    public function exportPdf()
    {
        $payments = SpotlightPayment::with(['user', 'product'])->orderByDesc('created_at')->limit(500)->get();
        $pdf = Pdf::loadView('backend.layout.spotlight.export_pdf', compact('payments'));
        return $pdf->download('spotlight_payments_report_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export payment logs to Excel.
     */
    public function exportExcel()
    {
        $payments = SpotlightPayment::with(['user', 'product'])->orderByDesc('created_at')->get();
        $filename = "spotlight_payments_" . date('Y-m-d') . ".xls";

        $html = '<table border="1">';
        $html .= '<tr><th>Date</th><th>Transaction ID</th><th>User Name</th><th>User Email</th><th>Product Title</th><th>Product ID</th><th>Plan</th><th>Currency</th><th>Amount</th><th>Status</th><th>Start Date</th><th>End Date</th></tr>';
        
        foreach ($payments as $payment) {
            $html .= '<tr>';
            $html .= '<td>' . $payment->created_at->format('Y-m-d H:i:s') . '</td>';
            $html .= '<td>' . htmlspecialchars($payment->stripe_payment_intent_id) . '</td>';
            $html .= '<td>' . htmlspecialchars($payment->user->name ?? 'N/A') . '</td>';
            $html .= '<td>' . htmlspecialchars($payment->user->email ?? 'N/A') . '</td>';
            $html .= '<td>' . htmlspecialchars($payment->product->title ?? 'N/A') . '</td>';
            $html .= '<td>' . $payment->product_id . '</td>';
            $html .= '<td>' . htmlspecialchars($payment->boost_plan) . '</td>';
            $html .= '<td>' . strtoupper($payment->currency) . '</td>';
            $html .= '<td>' . $payment->total_fee . '</td>';
            $html .= '<td>' . ucfirst($payment->status) . '</td>';
            $html .= '<td>' . ($payment->spotlight_start_at ? $payment->spotlight_start_at->format('Y-m-d') : 'N/A') . '</td>';
            $html .= '<td>' . ($payment->spotlight_end_at ? $payment->spotlight_end_at->format('Y-m-d') : 'N/A') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export payment logs to JSON.
     */
    public function exportJson()
    {
        $payments = SpotlightPayment::with(['user', 'product'])->orderByDesc('created_at')->get();
        $filename = "spotlight_payments_" . date('Y-m-d') . ".json";

        return response()->json($payments)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
