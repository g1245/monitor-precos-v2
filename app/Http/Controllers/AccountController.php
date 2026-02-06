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

        return view('account.dashboard', [
            'user' => $user,
        ]);
    }

    /**
     * Show saved products.
     */
    public function savedProducts()
    {
        $user = Auth::user();
        $savedProducts = $user->savedProducts()
            ->with('product.store')
            ->latest()
            ->paginate(20);

        return view('account.saved-products', [
            'savedProducts' => $savedProducts,
        ]);
    }

    /**
     * Show price alerts.
     */
    public function priceAlerts()
    {
        $user = Auth::user();
        $priceAlerts = $user->priceAlerts()
            ->with('product.store')
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
