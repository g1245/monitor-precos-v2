<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    public function index()
    {
        $highlights = Highlight::latest()->limit(10)->get();

        // Get top discounted products from cache
        // If cache is empty, return empty collection
        $topDiscountedProducts = Cache::get('welcome.top_discounted_products', collect());

        return view('welcome.index', compact('highlights', 'topDiscountedProducts'));
    }
}
