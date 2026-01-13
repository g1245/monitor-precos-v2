<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the specified product with price comparison.
     */
    public function index(int $id, string $slug, Request $request)
    {
        $product = Product::query()
            ->with(['departments', 'attributes'])
            ->where('id', $id)
            ->active()
            ->firstOrFail();

        // Mock data for stores comparison - in real implementation, this would come from external APIs or database
        $storeOffers = $this->getMockStoreOffers($product);

        // Get real price history data
        $priceHistory = $this->getPriceHistory($product);

        return view('product.index', [
            'product' => $product,
            'storeOffers' => $storeOffers,
            'priceHistory' => $priceHistory,
            'slug' => $slug,
        ]);
    }

    /**
     * Generate mock store offers for demonstration.
     */
    private function getMockStoreOffers(Product $product): array
    {
        return [
            [
                'id' => 1,
                'store_name' => $product->store->name,
                'store_logo' => $product->store->logo_url,
                'price' => $product->price,
                'installment_price' => 'ou 10x de R$ ' . number_format($product->price / 10, 2, ',', '.'),
                // 'discount_percentage' => 10,
                // 'cashback' => 'R$ 25,00',
                // 'coupon' => 'PRECOEXCLUSIVO',
                // 'is_best_price' => true,
                'store_rating' => 4.8,
                'link' => $product->deep_link
            ],
        ];
    }

    /**
     * Get price history for the product.
     */
    private function getPriceHistory(Product $product): array
    {
        $histories = $product->priceHistories()
            ->orderBy('created_at', 'asc')
            ->get();

        if ($histories->isEmpty()) {
            return [
                'data' => [],
                'lowest_price' => null,
                'highest_price' => null,
                'current_price' => $product->price,
                'has_history' => false,
            ];
        }

        $data = $histories->map(function ($history) {
            return [
                'date' => $history->created_at->format('Y-m-d'),
                'price' => $history->price,
                'formatted_date' => $history->created_at->format('d/m')
            ];
        })->toArray();

        return [
            'data' => $data,
            'lowest_price' => $histories->min('price'),
            'highest_price' => $histories->max('price'),
            'current_price' => $product->price,
            'has_history' => true,
        ];
    }

    /**
     * Generate mock price history for demonstration.
     */
    private function getMockPriceHistory(Product $product): array
    {
        $currentPrice = $product->price;
        $historicalPrices = [];

        // Generate 90 days of price history
        for ($i = 90; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $variance = rand(-500, 300);
            $price = max($currentPrice + $variance, $currentPrice * 0.7); // Don't go below 70% of current price

            $historicalPrices[] = [
                'date' => $date->format('Y-m-d'),
                'price' => $price,
                'formatted_date' => $date->format('d/m')
            ];
        }

        return [
            'data' => $historicalPrices,
            'lowest_price' => min(array_column($historicalPrices, 'price')),
            'highest_price' => max(array_column($historicalPrices, 'price')),
            'current_price' => $currentPrice,
        ];
    }
}
