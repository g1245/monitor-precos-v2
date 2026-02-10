<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of all stores.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $stores = Store::query()
            ->where('has_public', true)
            ->orderBy('name')
            ->get();

        return view('store.index', [
            'stores' => $stores,
        ]);
    }

    /**
     * Display the store page with its products.
     *
     * @param string $slug
     * @param int $id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(string $slug, int $id, Request $request)
    {
        // Get filter parameters from request
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');
        $brand = $request->input('brand');

        $store = Store::query()
            ->where('has_public', true)
            ->with(['products' => function ($query) use ($minPrice, $maxPrice, $brand) {
                $query->where('discount_percentage', '>', 0)
                    ->when($minPrice !== null, function ($q) use ($minPrice) {
                        return $q->where('price', '>=', $minPrice);
                    })
                    ->when($maxPrice !== null, function ($q) use ($maxPrice) {
                        return $q->where('price', '<=', $maxPrice);
                    })
                    ->when($brand !== null && $brand !== '', function ($q) use ($brand) {
                        return $q->where('brand', 'LIKE', "%{$brand}%");
                    })
                    ->orderBy('discount_percentage', 'desc')
                    ->limit(20);
            }])
            ->findOrFail($id);

        return view('store.show', [
            'store' => $store,
        ]);
    }

    /**
     * Serve the store logo image.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function logo(int $id)
    {
        $store = Store::findOrFail($id);

        if (!$store->logo) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $store->logo));
    }
}
