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
        $minPriceRegular = $request->input('minPriceRegular');
        $maxPriceRegular = $request->input('maxPriceRegular');
        $brand = $request->input('brand');
        $storeIdFilter = $request->input('storeId');

        $store = Store::query()
            ->where('has_public', true)
            ->with(['products' => function ($query) use ($minPrice, $maxPrice, $minPriceRegular, $maxPriceRegular, $brand, $storeIdFilter) {
                $query->where('discount_percentage', '>', 0)
                    ->when($minPrice !== null, function ($q) use ($minPrice) {
                        return $q->where('price', '>=', $minPrice);
                    })
                    ->when($maxPrice !== null, function ($q) use ($maxPrice) {
                        return $q->where('price', '<=', $maxPrice);
                    })
                    ->when($minPriceRegular !== null, function ($q) use ($minPriceRegular) {
                        return $q->where('price_regular', '>=', $minPriceRegular);
                    })
                    ->when($maxPriceRegular !== null, function ($q) use ($maxPriceRegular) {
                        return $q->where('price_regular', '<=', $maxPriceRegular);
                    })
                    ->when($brand !== null && $brand !== '', function ($q) use ($brand) {
                        return $q->where('brand', 'LIKE', "%{$brand}%");
                    })
                    ->when($storeIdFilter !== null, function ($q) use ($storeIdFilter) {
                        return $q->where('store_id', $storeIdFilter);
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
