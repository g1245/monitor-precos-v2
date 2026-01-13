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
        $basePrice = $product->price;
        
        return [
            [
                'id' => 1,
                'store_name' => 'KaBuM!',
                'store_logo' => 'https://via.placeholder.com/40x40/ff6600/ffffff?text=K',
                'price' => $basePrice,
                'installment_price' => 'ou 10x de R$ ' . number_format($basePrice / 10, 2, ',', '.'),
                'discount_percentage' => 10,
                'cashback' => 'R$ 25,00',
                'coupon' => 'PRECOEXCLUSIVO',
                'is_best_price' => true,
                'store_rating' => 4.8,
                'link' => 'https://kabum.com.br'
            ],
            [
                'id' => 2,
                'store_name' => 'Magazine Luiza',
                'store_logo' => 'https://via.placeholder.com/40x40/0066cc/ffffff?text=M',
                'price' => $basePrice + 500,
                'installment_price' => 'ou 10x de R$ ' . number_format(($basePrice + 500) / 10, 2, ',', '.'),
                'discount_percentage' => 1,
                'cashback' => 'R$ 35,61',
                'coupon' => null,
                'is_best_price' => false,
                'store_rating' => 4.5,
                'link' => 'https://magazineluiza.com.br'
            ],
            [
                'id' => 3,
                'store_name' => 'Amazon',
                'store_logo' => 'https://via.placeholder.com/40x40/000000/ffffff?text=A',
                'price' => $basePrice + 1000,
                'installment_price' => 'à vista',
                'discount_percentage' => null,
                'cashback' => null,
                'coupon' => null,
                'is_best_price' => false,
                'store_rating' => 4.9,
                'link' => 'https://amazon.com.br'
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
