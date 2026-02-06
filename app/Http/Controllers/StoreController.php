<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of all stores.
     *
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
     * @return \Illuminate\View\View
     */
    public function show(string $slug, int $id, Request $request)
    {
        $store = Store::query()
            ->where('has_public', true)
            ->with(['products' => function ($query) {
                $query->limit(20);
            }])
            ->findOrFail($id);

        return view('store.show', [
            'store' => $store,
        ]);
    }

    /**
     * Display all products from a store with pagination.
     *
     * @return \Illuminate\View\View
     */
    public function products(string $slug, int $id, Request $request)
    {
        $store = Store::query()
            ->where('has_public', true)
            ->findOrFail($id);

        return view('store.products', [
            'store' => $store,
        ]);
    }

    /**
     * Serve the store logo image.
     *
     * @return \Illuminate\Http\Response
     */
    public function logo(int $id)
    {
        $store = Store::findOrFail($id);

        if (! $store->logo) {
            abort(404);
        }

        return response()->file(storage_path('app/public/'.$store->logo));
    }
}
