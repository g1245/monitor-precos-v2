<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get counts for dashboard statistics
        $totalWishes = $user->userWishProducts()->count();
        $wishesWithAlerts = $user->userWishProducts()
            ->whereNotNull('target_price')
            ->where('is_active', true)
            ->count();

        return view('account.dashboard', [
            'user' => $user,
            'totalWishes' => $totalWishes,
            'wishesWithAlerts' => $wishesWithAlerts,
        ]);
    }

    /**
     * Show user's wishlist (saved products).
     */
    public function wishlist()
    {
        $user = Auth::user();
        $userWishes = $user->userWishProducts()
            ->with('product.store')
            ->latest()
            ->paginate(20);

        return view('account.wishlist', [
            'userWishes' => $userWishes,
        ]);
    }

    /**
     * Show price alerts (wishes with target price).
     */
    public function priceAlerts()
    {
        $user = Auth::user();
        $priceAlerts = $user->userWishProducts()
            ->with('product.store')
            ->whereNotNull('target_price')
            ->where('is_active', true)
            ->latest()
            ->paginate(20);

        return view('account.price-alerts', [
            'priceAlerts' => $priceAlerts,
        ]);
    }

    /**
     * Show browsing history.
     */
    public function browsingHistory()
    {
        $user = Auth::user();
        $history = $user->browsingHistory()
            ->with(['product', 'department', 'store'])
            ->latest('visited_at')
            ->paginate(50);

        return view('account.browsing-history', [
            'history' => $history,
        ]);
    }
}
