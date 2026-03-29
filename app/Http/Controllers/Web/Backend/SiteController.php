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
        $data['spotlightedProducts'] = SpotlightPayment::where('status', 'paid')
                                              ->distinct('product_id')
                                              ->count('product_id');
                                              
        $data['totalMatches'] = DB::table('matches')->count();
        $data['totalPickups'] = Pickup::count();
        $data['completedPickups'] = Pickup::where('status', 'completed')->count();
        $data['totalReports'] = Report::count();

        // Revenue (Spotlight + Garage Sales)
        $spotlightRevenue = SpotlightPayment::where('status', 'paid')->sum('total_fee');
        $garageRevenue = GarageSale::where('payment_status', 'completed')->sum('total_fee');
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
                ->whereMonth('created_at', $i)->sum('total_fee');
            $garageSaleRev = GarageSale::where('payment_status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)->sum('total_fee');
                
            $orders[] = $spotlightCount + $garageSaleCount;
            $earnings[] = $spotlightRev + $garageSaleRev;
        }
        
        $data['totalOrders'] = array_sum($orders);
        $data['orders'] = $orders;
        $data['earnings'] = $earnings;
        $data['months'] = $months;
        $data['refunds'] = array_fill(0, 12, 0); // Placeholder for refunds
        
        $cityExpression = 'CASE 
                    WHEN pickup_location IS NULL OR TRIM(pickup_location) = "" THEN "Unknown"
                    ELSE TRIM(SUBSTRING_INDEX(pickup_location, ",", 1))
                END';

        $data['topGarageCities'] = GarageSale::selectRaw("$cityExpression as city_name, count(*) as total")
            ->whereNotNull('pickup_location')
            ->where('pickup_location', '!=', '')
            ->groupByRaw($cityExpression)
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function($item) {
                // Ensure field matches the expected view field properly
                $item->pickup_location = $item->city_name;
                return $item;
            });
            
        $data['topSpotlightCities'] = Product::selectRaw("$cityExpression as city_name, count(*) as total")
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                      ->from('spotlight_payments')
                      ->where('status', 'paid');
            })
            ->whereNotNull('pickup_location')
            ->where('pickup_location', '!=', '')
            ->groupByRaw($cityExpression)
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $item->pickup_location = $item->city_name;
                return $item;
            });

        return view("backend.index", $data);
    }
}
