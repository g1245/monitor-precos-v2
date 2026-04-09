<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TopDiscountsSyncService
{
    /**
     * The department ID for top discounts (price highlight).
     */
    public const DEPARTMENT_ID = 154;

    /**
     * Sync products with biggest price reductions to the top discounts department.
     * When $storeId is provided, only removes and re-attaches products from that store,
     * keeping products from other stores intact.
     *
     * @param int|null $storeId Restrict sync to a specific store, or null for all stores.
     * @return bool Returns false if the department does not exist, true otherwise.
     */
    public static function sync(?int $storeId = null): bool
    {
        $department = Department::find(self::DEPARTMENT_ID);

        if (!$department) {
            Log::error('TopDiscountsSyncService: Department with ID ' . self::DEPARTMENT_ID . ' does not exist.');

            return false;
        }

        // Detach products from the department (scoped to store if provided)
        $deleteQuery = DB::table('departments_products')
            ->where('department_id', self::DEPARTMENT_ID);

        if ($storeId !== null) {
            $deleteQuery->whereIn('product_id', function ($query) use ($storeId) {
                $query->select('id')->from('products')->where('store_id', $storeId);
            });
        }

        $deleteQuery->delete();

        // Get top discounted products (scoped to store if provided)
        $query = Product::query()
            ->fromPublicStore()
            ->parentProducts()
            ->withRecentPriceChange();

        if ($storeId !== null) {
            $query->where('store_id', $storeId);
        }

        $topDiscountedProducts = $query->get();

        if ($topDiscountedProducts->isEmpty()) {
            Log::info('TopDiscountsSyncService: No products with price reductions found.', [
                'store_id' => $storeId,
            ]);

            return true;
        }

        // Attach products to the department
        $department->products()->attach(
            $topDiscountedProducts->pluck('id')->toArray()
        );

        Log::info('TopDiscountsSyncService: Synced ' . $topDiscountedProducts->count() . ' products to department ' . self::DEPARTMENT_ID . '.', [
            'store_id' => $storeId,
        ]);

        return true;
    }
}
