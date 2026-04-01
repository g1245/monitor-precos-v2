<?php

namespace App\Services;

use App\Dto\ProductDto;
use App\Models\Store;
use Illuminate\Support\Str;

class ProductDtoResolver
{
    /**
     * Resolve the appropriate ProductDto class for a given store.
     *
     * Performs auto-discovery by looking for a class named
     * `App\Dto\Products\{Studly(SyncStoreName)}ProductDto`.
     * Falls back to the base `ProductDto` if no store-specific class is found.
     *
     * To add support for a new store, simply create:
     * `app/Dto/Products/{StoreName}ProductDto.php` extending `ProductDto`.
     *
     * @param Store $store
     * @return class-string<ProductDto>
     */
    public static function resolve(Store $store): string
    {
        $storeName = $store->internal_name ?? '';
        $candidate = 'App\\Dto\\Products\\' . Str::studly($storeName) . 'ProductDto';

        if (class_exists($candidate) && is_subclass_of($candidate, ProductDto::class)) {
            return $candidate;
        }

        return ProductDto::class;
    }
}
