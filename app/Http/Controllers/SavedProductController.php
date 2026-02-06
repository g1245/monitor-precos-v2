<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SavedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedProductController extends Controller
{
    /**
     * Save a product for the authenticated user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        // Check if already saved
        if ($user->hasSavedProduct($productId)) {
            return response()->json([
                'message' => 'Produto já está nos seus salvos',
                'saved' => true,
            ]);
        }

        SavedProduct::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json([
            'message' => 'Produto salvo com sucesso!',
            'saved' => true,
        ]);
    }

    /**
     * Remove a saved product.
     */
    public function destroy(Request $request, int $productId)
    {
        $user = Auth::user();

        $deleted = SavedProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Produto removido dos salvos',
                'saved' => false,
            ]);
        }

        return response()->json([
            'message' => 'Produto não encontrado nos salvos',
            'saved' => false,
        ], 404);
    }

    /**
     * Check if a product is saved.
     */
    public function check(int $productId)
    {
        $user = Auth::user();

        return response()->json([
            'saved' => $user ? $user->hasSavedProduct($productId) : false,
        ]);
    }
}
