<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserWishProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWishProductController extends Controller
{
    /**
     * Add a product to user's wishlist (with optional price alert).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'target_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = Auth::user();
        $productId = $validated['product_id'];

        // Check if wish already exists
        $existingWish = UserWishProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingWish) {
            // Update existing wish with new target price
            $existingWish->update([
                'target_price' => $validated['target_price'] ?? null,
                'is_active' => true,
            ]);

            return response()->json([
                'message' => $validated['target_price'] 
                    ? 'Produto salvo e alerta de preço atualizado!' 
                    : 'Produto já está na sua lista de desejos',
                'wished' => true,
                'has_alert' => $existingWish->hasPriceAlert(),
                'wish' => $existingWish,
            ]);
        }

        // Create new wish
        $wish = UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'target_price' => $validated['target_price'] ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => $wish->hasPriceAlert() 
                ? 'Produto salvo com alerta de preço!' 
                : 'Produto adicionado à sua lista de desejos!',
            'wished' => true,
            'has_alert' => $wish->hasPriceAlert(),
            'wish' => $wish,
        ], 201);
    }

    /**
     * Remove a product from wishlist.
     */
    public function destroy(int $productId)
    {
        $user = Auth::user();

        $deleted = UserWishProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Produto removido da lista de desejos',
                'wished' => false,
            ]);
        }

        return response()->json([
            'message' => 'Produto não encontrado na lista de desejos',
            'wished' => false,
        ], 404);
    }

    /**
     * Check if a product is in wishlist and get details.
     */
    public function check(int $productId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'wished' => false,
                'has_alert' => false,
            ]);
        }

        $wish = UserWishProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        return response()->json([
            'wished' => $wish !== null,
            'has_alert' => $wish ? $wish->hasPriceAlert() : false,
            'wish' => $wish,
        ]);
    }

    /**
     * Update only the price alert for a wished product.
     */
    public function updatePriceAlert(Request $request, int $productId)
    {
        $validated = $request->validate([
            'target_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = Auth::user();

        $wish = UserWishProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if (!$wish) {
            return response()->json([
                'message' => 'Produto não está na sua lista de desejos',
            ], 404);
        }

        $wish->update([
            'target_price' => $validated['target_price'] ?? null,
            'is_active' => $validated['target_price'] !== null,
        ]);

        return response()->json([
            'message' => $wish->hasPriceAlert() 
                ? 'Alerta de preço atualizado!' 
                : 'Alerta de preço removido',
            'wish' => $wish,
        ]);
    }

    /**
     * Toggle price alert active status.
     */
    public function toggleAlert(int $productId)
    {
        $user = Auth::user();

        $wish = UserWishProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if (!$wish) {
            return response()->json([
                'message' => 'Produto não está na sua lista de desejos',
            ], 404);
        }

        if (!$wish->hasPriceAlert()) {
            return response()->json([
                'message' => 'Este produto não tem alerta de preço configurado',
            ], 400);
        }

        $wish->update(['is_active' => !$wish->is_active]);

        return response()->json([
            'message' => $wish->is_active ? 'Alerta ativado' : 'Alerta desativado',
            'wish' => $wish,
        ]);
    }
}
