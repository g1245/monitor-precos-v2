<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AccountController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * Show the user account page.
     */
    public function index()
    {
        $user = Auth::user();
        
        $savedProducts = $user->savedProducts()
            ->with('product')
            ->latest()
            ->paginate(12);

        $priceAlerts = $user->priceAlerts()
            ->with('product')
            ->active()
            ->latest()
            ->get();

        $recentVisits = $user->visits()
            ->with('visitable')
            ->latest()
            ->take(20)
            ->get();

        return view('account.index', compact('savedProducts', 'priceAlerts', 'recentVisits'));
    }

    /**
     * Toggle save product.
     */
    public function toggleSaveProduct(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $savedProduct = $user->savedProducts()->where('product_id', $productId)->first();

        if ($savedProduct) {
            $savedProduct->delete();
            return response()->json(['saved' => false, 'message' => 'Produto removido dos salvos']);
        }

        $user->savedProducts()->create(['product_id' => $productId]);
        return response()->json(['saved' => true, 'message' => 'Produto salvo com sucesso']);
    }

    /**
     * Toggle price alert.
     */
    public function togglePriceAlert(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $priceAlert = $user->priceAlerts()->where('product_id', $productId)->first();

        if ($priceAlert) {
            $priceAlert->update(['is_active' => !$priceAlert->is_active]);
            return response()->json([
                'active' => $priceAlert->is_active,
                'message' => $priceAlert->is_active ? 'Alerta de preço ativado' : 'Alerta de preço desativado'
            ]);
        }

        $user->priceAlerts()->create(['product_id' => $productId]);
        return response()->json(['active' => true, 'message' => 'Alerta de preço ativado']);
    }
}
