<?php

namespace Tests\Unit\Services;

use App\Dto\ProductAttributeDto;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Store;
use App\Services\ProductAttributeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAttributeServiceTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test product
        $store = Store::factory()->create();
        $this->product = Product::factory()->create([
            'store_id' => $store->id,
        ]);
    }

    /**
     * Test sync creates new attributes.
     */
    public function test_sync_creates_new_attributes(): void
    {
        $dto = new ProductAttributeDto(
            productId: $this->product->id,
            inStock: 'yes',
            stockQuantity: '10',
            condition: 'new'
        );

        ProductAttributeService::sync($dto);

        $this->assertDatabaseHas('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'in_stock',
            'description' => 'yes',
        ]);

        $this->assertDatabaseHas('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'stock_quantity',
            'description' => '10',
        ]);

        $this->assertDatabaseHas('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'condition',
            'description' => 'new',
        ]);
    }

    /**
     * Test sync updates existing attributes.
     */
    public function test_sync_updates_existing_attributes(): void
    {
        // Create an existing attribute
        ProductAttribute::create([
            'product_id' => $this->product->id,
            'key' => 'in_stock',
            'description' => 'no',
        ]);

        $dto = new ProductAttributeDto(
            productId: $this->product->id,
            inStock: 'yes',
        );

        ProductAttributeService::sync($dto);

        $this->assertDatabaseHas('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'in_stock',
            'description' => 'yes',
        ]);

        // Ensure only one record exists for this key
        $count = ProductAttribute::where('product_id', $this->product->id)
            ->where('key', 'in_stock')
            ->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Test sync does not create attributes for null values.
     */
    public function test_sync_does_not_create_attributes_for_null_values(): void
    {
        $dto = new ProductAttributeDto(
            productId: $this->product->id,
            inStock: 'yes',
            stockQuantity: null,
            condition: null
        );

        ProductAttributeService::sync($dto);

        $this->assertDatabaseHas('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'in_stock',
        ]);

        $this->assertDatabaseMissing('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'stock_quantity',
        ]);

        $this->assertDatabaseMissing('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'condition',
        ]);
    }

    /**
     * Test createOrUpdate creates new attribute.
     */
    public function test_create_or_update_creates_new_attribute(): void
    {
        $attribute = ProductAttributeService::createOrUpdate(
            $this->product->id,
            'test_key',
            'test_value'
        );

        $this->assertInstanceOf(ProductAttribute::class, $attribute);
        $this->assertEquals('test_key', $attribute->key);
        $this->assertEquals('test_value', $attribute->description);

        $this->assertDatabaseHas('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'test_key',
            'description' => 'test_value',
        ]);
    }

    /**
     * Test createOrUpdate updates existing attribute.
     */
    public function test_create_or_update_updates_existing_attribute(): void
    {
        ProductAttribute::create([
            'product_id' => $this->product->id,
            'key' => 'test_key',
            'description' => 'old_value',
        ]);

        $attribute = ProductAttributeService::createOrUpdate(
            $this->product->id,
            'test_key',
            'new_value'
        );

        $this->assertEquals('new_value', $attribute->description);

        $this->assertDatabaseHas('products_attributes', [
            'product_id' => $this->product->id,
            'key' => 'test_key',
            'description' => 'new_value',
        ]);

        // Ensure only one record exists
        $count = ProductAttribute::where('product_id', $this->product->id)
            ->where('key', 'test_key')
            ->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Test deleteAll removes all attributes for a product.
     */
    public function test_delete_all_removes_all_attributes(): void
    {
        ProductAttribute::create([
            'product_id' => $this->product->id,
            'key' => 'key1',
            'description' => 'value1',
        ]);

        ProductAttribute::create([
            'product_id' => $this->product->id,
            'key' => 'key2',
            'description' => 'value2',
        ]);

        ProductAttributeService::deleteAll($this->product->id);

        $count = ProductAttribute::where('product_id', $this->product->id)->count();
        $this->assertEquals(0, $count);
    }

    /**
     * Test getAttributesForProduct returns correct key-value pairs.
     */
    public function test_get_attributes_for_product_returns_correct_pairs(): void
    {
        ProductAttribute::create([
            'product_id' => $this->product->id,
            'key' => 'color',
            'description' => 'red',
        ]);

        ProductAttribute::create([
            'product_id' => $this->product->id,
            'key' => 'size',
            'description' => 'M',
        ]);

        $attributes = ProductAttributeService::getAttributesForProduct($this->product->id);

        $this->assertIsArray($attributes);
        $this->assertArrayHasKey('color', $attributes);
        $this->assertArrayHasKey('size', $attributes);
        $this->assertEquals('red', $attributes['color']);
        $this->assertEquals('M', $attributes['size']);
    }

    /**
     * Test getAttributesForProduct returns empty array when no attributes.
     */
    public function test_get_attributes_for_product_returns_empty_array(): void
    {
        $attributes = ProductAttributeService::getAttributesForProduct($this->product->id);

        $this->assertIsArray($attributes);
        $this->assertEmpty($attributes);
    }
}
