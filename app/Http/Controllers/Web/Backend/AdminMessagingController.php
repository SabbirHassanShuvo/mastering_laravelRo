<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Matche;
use App\Models\Message;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMessagingController extends Controller
{
    public function analytics()
    {
        // 1. Top Matched Products (Products with most matches)
        $topMatchedProducts = Matche::select('product_id', DB::raw('count(*) as matches_count'))
            ->groupBy('product_id')
            ->orderByDesc('matches_count')
            ->with('product')
            ->take(10)
            ->get();

        // 2. Power Users (Users with most product listings)
        $powerUsers = Product::select('user_id', DB::raw('count(*) as products_count'))
            ->groupBy('user_id')
            ->orderByDesc('products_count')
            ->with('user')
            ->take(10)
            ->get();

        // 3. Active Chatters (Users who send the most chat requests / initiate conversations)
        $activeChatters = Conversation::select('user_one_id', DB::raw('count(*) as conversations_count'))
            ->groupBy('user_one_id')
            ->orderByDesc('conversations_count')
            ->with('userOne')
            ->take(10)
            ->get();

        // 4. Most Chatted Products (Products that generated the most conversations)
        $mostChattedProducts = Conversation::select('product_id', DB::raw('count(*) as conversations_count'))
            ->groupBy('product_id')
            ->orderByDesc('conversations_count')
            ->with('product')
            ->take(10)
            ->get();

        // 5. Pickup Request Statistics
        $pickupStats = Pickup::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // 6. Overall System Totals
        $totals = [
            'total_matches'        => Matche::count(),
            'total_conversations'  => Conversation::count(),
            'total_messages'       => Message::count(),
            'total_pickups'        => Pickup::count(),
            'total_contact_shares' => ContactShare::count(),
        ];

        return view('backend.messaging.analytics', compact(
            'topMatchedProducts',
            'powerUsers',
            'activeChatters',
            'mostChattedProducts',
            'pickupStats',
            'totals'
        ));
    }

    public function conversations()
    {
        $conversations = Conversation::with(['product', 'userOne', 'userTwo'])
            ->withCount('messages')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('backend.messaging.conversations', compact('conversations'));
    }

    public function pickups()
    {
        $pickups = Pickup::with(['product', 'requester', 'receiver'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('backend.messaging.pickups', compact('pickups'));
    }
}
