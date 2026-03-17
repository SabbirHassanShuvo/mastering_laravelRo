<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\GarageSale;
use App\Models\SpotlightPayment;
use App\Models\Pickup;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index(){
        $data = [];
        
        // Basic Stats
        $data['totalUsers'] = User::count();
        $data['suspendedUsers'] = User::whereNotNull('suspended_at')->count();
        $data['totalProducts'] = Product::count();
        $data['totalGarageSales'] = GarageSale::count();
        $data['spotlightedProducts'] = Product::where('is_spotlighted', true)
                                              ->orWhere('boost_count', '>', 0)->count();
                                              
        $data['totalMatches'] = DB::table('matches')->count();
        $data['totalPickups'] = Pickup::count();
        $data['completedPickups'] = Pickup::where('status', 'completed')->count();
        $data['totalReports'] = Report::count();

        // Revenue (Spotlight + Garage Sales)
        $spotlightRevenue = SpotlightPayment::where('status', 'paid')->sum('amount');
        $garageRevenue = GarageSale::where('payment_status', 'completed')->sum('posting_fee'); // assuming posting_fee or total_fee
        $data['totalRevenue'] = $spotlightRevenue + $garageRevenue;
        
        // Charts Data: Orders (Spotlights/Garage Sales) and Earnings by Month for current year
        $currentYear = Carbon::now()->year;
        
        $orders = [];
        $earnings = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        for ($i = 1; $i <= 12; $i++) {
            $spotlightCount = SpotlightPayment::where('status', 'paid')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)->count();
            $garageSaleCount = GarageSale::where('payment_status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)->count();
                
            $spotlightRev = SpotlightPayment::where('status', 'paid')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)->sum('amount');
            $garageSaleRev = GarageSale::where('payment_status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)->sum('posting_fee');
                
            $orders[] = $spotlightCount + $garageSaleCount;
            $earnings[] = $spotlightRev + $garageSaleRev;
        }
        
        $data['totalOrders'] = array_sum($orders);
        $data['orders'] = $orders;
        $data['earnings'] = $earnings;
        $data['months'] = $months;
        $data['refunds'] = array_fill(0, 12, 0); // Placeholder for refunds
        
        // Location-based Stats
        $data['topGarageCities'] = GarageSale::select('pickup_location', DB::raw('count(*) as total'))
            ->whereNotNull('pickup_location')
            ->groupBy('pickup_location')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
            
        $data['topSpotlightCities'] = Product::where(function($q) {
                $q->where('is_spotlighted', true)
                  ->orWhere('boost_count', '>', 0);
            })
            ->select('pickup_location', DB::raw('count(*) as total'))
            ->whereNotNull('pickup_location')
            ->groupBy('pickup_location')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view("backend.index", $data);
    }
}
