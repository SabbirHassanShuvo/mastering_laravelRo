<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GarageSale;
use App\Models\Message;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\Report;
use App\Models\SpotlightPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        $data = [];
        $currentYear = Carbon::now()->year;

        // ── Basic Stats ────────────────────────────────────────────────────────
        $data['totalUsers']         = User::count();
        $data['verifiedUsers']      = User::whereNotNull('email_verified_at')->count();
        $data['suspendedUsers']     = User::whereNotNull('suspended_at')->count();
        $data['totalProducts']      = Product::count();
        $data['totalGarageSales']   = GarageSale::count();
        $data['spotlightedProducts']= SpotlightPayment::where('status', 'paid')
                                        ->distinct()
                                        ->count('product_id');
        $data['totalMatches']       = DB::table('matches')->count();
        $data['totalPickups']       = Pickup::count();
        $data['completedPickups']   = Pickup::where('status', 'completed')->count();
        $data['totalReports']       = Report::count();
        $data['totalMessages']      = Message::count();

        // ── Conversion Rate ────────────────────────────────────────────────────
        $data['matchConversionRate'] = $data['totalMatches'] > 0
            ? round(($data['completedPickups'] / $data['totalMatches']) * 100, 2)
            : 0;

        // ── Revenue ────────────────────────────────────────────────────────────
        $spotlightRevenue = SpotlightPayment::where('status', 'paid')->sum('total_fee');
        $garageRevenue    = GarageSale::where('payment_status', 'completed')->sum('total_fee');
        $data['totalRevenue']   = $spotlightRevenue + $garageRevenue;
        $data['revenueSources'] = [
            'Spotlight'   => $spotlightRevenue,
            'Garage Sales' => $garageRevenue,
        ];

        // ── Monthly Trend Charts (single query per model, no loop) ─────────────
        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        // Spotlight orders + revenue by month
        $spotlightByMonth = SpotlightPayment::where('status', 'paid')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as cnt, SUM(total_fee) as rev')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Garage sale orders + revenue by month
        $garageByMonth = GarageSale::where('payment_status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as cnt, SUM(total_fee) as rev')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Pickup requests by month (REPLACING original orders count logic)
        $pickupsByMonth = Pickup::whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as cnt')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // User signups by month
        $usersByMonth = User::whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as cnt')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Message activity by month
        $messagesByMonth = Message::whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as cnt')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Build 12-month arrays
        $orders       = [];
        $earnings     = [];
        $userGrowth   = [];
        $messageActivity = [];

        for ($i = 1; $i <= 12; $i++) {
            // Using pickup request count for 'orders' name consistent with user feedback
            $orders[]          = $pickupsByMonth[$i]->cnt ?? 0;
            $earnings[]        = ($spotlightByMonth[$i]->rev ?? 0) + ($garageByMonth[$i]->rev ?? 0);
            $userGrowth[]      = $usersByMonth[$i]->cnt ?? 0;
            $messageActivity[] = $messagesByMonth[$i]->cnt ?? 0;
        }

        $data['totalOrders']     = array_sum($orders);
        $data['months']          = $months;
        $data['orders']          = $orders;
        $data['earnings']        = $earnings;
        $data['userGrowth']      = $userGrowth;
        $data['messageActivity'] = $messageActivity;

        // ── Distributions ──────────────────────────────────────────────────────
        $data['categoryDistribution'] = Category::withCount('products')
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();
            
        $data['pickupStatusDistribution'] = Pickup::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        $data['paymentStatusDistribution'] = SpotlightPayment::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        // ── Recent Activities (unified feed) ───────────────────────────────────
        $recentUsers = User::latest()->limit(3)->get()->map(fn($u) => [
            'type'  => 'User',
            'title' => 'New User: ' . $u->name,
            'time'  => $u->created_at,
            'icon'  => 'ri-user-add-line',
            'color' => 'primary',
        ]);

        $recentProducts = Product::latest()->limit(3)->get()->map(fn($p) => [
            'type'  => 'Product',
            'title' => 'New Listing: ' . $p->name,
            'time'  => $p->created_at,
            'icon'  => 'ri-shopping-bag-line',
            'color' => 'success',
        ]);

        $recentPayments = SpotlightPayment::where('status', 'paid')->latest()->limit(3)->get()->map(fn($pay) => [
            'type'  => 'Payment',
            'title' => 'Spotlight Payment: $' . number_format($pay->total_fee, 2),
            'time'  => $pay->created_at,
            'icon'  => 'ri-money-dollar-circle-line',
            'color' => 'info',
        ]);

        $recentPickups = Pickup::latest()->limit(3)->get()->map(fn($pick) => [
            'type'  => 'Pickup',
            'title' => 'Pickup Scheduled: #' . $pick->id,
            'time'  => $pick->created_at,
            'icon'  => 'ri-truck-line',
            'color' => 'warning',
        ]);

        $recentReports = Report::latest()->limit(2)->get()->map(fn($r) => [
            'type'  => 'Report',
            'title' => 'Report Flagged: #' . $r->id,
            'time'  => $r->created_at,
            'icon'  => 'ri-error-warning-line',
            'color' => 'danger',
        ]);

        $data['recentActivities'] = $recentUsers
            ->concat($recentProducts)
            ->concat($recentPayments)
            ->concat($recentPickups)
            ->concat($recentReports)
            ->sortByDesc('time')
            ->take(5);

        return view('backend.index', $data);
    }
}