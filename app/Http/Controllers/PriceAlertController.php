<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceAlertController extends Controller
{
    /**
     * Create a price alert.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'target_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = Auth::user();

        // Check if alert already exists
        $existingAlert = PriceAlert::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingAlert) {
            $existingAlert->update([
                'target_price' => $validated['target_price'] ?? null,
                'is_active' => true,
            ]);

            return response()->json([
                'message' => 'Alerta de preço atualizado!',
                'alert' => $existingAlert,
            ]);
        }

        $alert = PriceAlert::create([
            'user_id' => $user->id,
            'product_id' => $validated['product_id'],
            'target_price' => $validated['target_price'] ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Alerta de preço criado com sucesso!',
            'alert' => $alert,
        ], 201);
    }

    /**
     * Deactivate a price alert.
     */
    public function destroy(int $productId)
    {
        $user = Auth::user();

        $alert = PriceAlert::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if (!$alert) {
            return response()->json([
                'message' => 'Alerta não encontrado',
            ], 404);
        }

        $alert->update(['is_active' => false]);

        return response()->json([
            'message' => 'Alerta de preço desativado',
        ]);
    }

    /**
     * Check if user has an alert for a product.
     */
    public function check(int $productId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['has_alert' => false]);
        }

        $alert = PriceAlert::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'has_alert' => $alert !== null,
            'alert' => $alert,
        ]);
    }
}
