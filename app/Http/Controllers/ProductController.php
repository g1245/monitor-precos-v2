<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(string $alias, string $productId, Request $request)
    {
        $product = Product::with([
            'images',
            'specifications',
            'departments'
        ])
        ->where('id', $productId)
        ->where('is_active', true)
        ->firstOrFail();

        $departmentFromUrl = null;
        if ($request->has('from_department')) {
            $departmentFromUrl = $product->departments()
                ->where('departments.id', $request->get('from_department'))
                ->first();
        } else {
            $departmentFromUrl = $product->departments()->first();
        }

        $similarProducts = Product::with('departments')
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->brand, function ($query) use ($product) {
                return $query->where('brand', $product->brand);
            })
            ->limit(10)
            ->get();

        return view('product.show', [
            'product' => $product,
            'department' => $departmentFromUrl,
            'similarProducts' => $similarProducts
        ]);
    }
}
