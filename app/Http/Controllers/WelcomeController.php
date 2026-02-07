<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function index()
    {
        $highlights = Highlight::latest()->limit(10)->get();

        // Get top discounted products from cache
        // If cache is empty, return empty collection
        $topDiscountedProducts = Cache::get('welcome.top_discounted_products', collect());

        // Process store logos for cached products
        $topDiscountedProducts->each(function ($product) {
            if ($product->store && $product->store->logo) {
                $product->store->logo_url = Storage::disk('public')->url($product->store->logo);
            }
        });

        return view('welcome.index', compact('highlights', 'topDiscountedProducts'));
    }
}
