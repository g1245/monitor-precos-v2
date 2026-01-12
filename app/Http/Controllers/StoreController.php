<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display the store page with its products.
     *
     * @param int $id
     * @param string $slug
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(string $slug, Request $request)
    {
        $store = Store::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('store.show', [
            'store' => $store,
        ]);
    }
}
