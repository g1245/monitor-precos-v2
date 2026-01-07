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
    public function show(int $id, string $slug, Request $request)
    {
        $store = Store::with(['products' => function ($query) {
            $query->active()
                ->orderBy('name')
                ->limit(50);
        }])
            ->withCount('products')
            ->findOrFail($id);

        // Verificar se o slug está correto, redirecionar se necessário
        if ($store->slug !== $slug) {
            return redirect()->route('store.show', [
                'id' => $store->id,
                'slug' => $store->slug
            ], 301);
        }

        return view('store.show', [
            'store' => $store,
        ]);
    }
}
