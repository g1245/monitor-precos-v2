<?php

namespace Tests\Unit\Dto;

use App\Dto\ProductAttributeDto;
use Tests\TestCase;

class ProductAttributeDtoTest extends TestCase
{
    /**
     * Test DTO can be created with all fields.
     */
    public function test_dto_can_be_created_with_all_fields(): void
    {
        $dto = new ProductAttributeDto(
            productId: 1,
            inStock: 'yes',
            stockQuantity: '10',
            condition: 'new',
            colour: 'red',
            color: 'blue',
            gender: 'unisex',
            size: 'M',
            sizeType: 'regular',
            custom1: 'value1',
            custom2: 'value2',
            custom4: 'value4',
            custom5: 'value5',
            custom6: 'value6',
            custom7: 'value7',
            custom8: 'value8',
            deliveryWeight: '1.5kg',
            fashionSuitableFor: 'adults',
            fashionSize: 'medium',
            merchantProductCategoryPath: 'clothing > shirts',
            productGTIN: '1234567890123',
            installment: '3x'
        );

        $this->assertEquals(1, $dto->productId);
        $this->assertEquals('yes', $dto->inStock);
        $this->assertEquals('10', $dto->stockQuantity);
    }

    /**
     * Test DTO can be created with only required fields.
     */
    public function test_dto_can_be_created_with_only_product_id(): void
    {
        $dto = new ProductAttributeDto(productId: 1);

        $this->assertEquals(1, $dto->productId);
        $this->assertNull($dto->inStock);
        $this->assertNull($dto->stockQuantity);
    }

    /**
     * Test toAttributesArray returns only non-null values.
     */
    public function test_to_attributes_array_returns_only_non_null_values(): void
    {
        $dto = new ProductAttributeDto(
            productId: 1,
            inStock: 'yes',
            stockQuantity: '10',
            condition: null,
            colour: 'red'
        );

        $attributes = $dto->toAttributesArray();

        $this->assertCount(3, $attributes);
        $this->assertContains(['key' => 'in_stock', 'description' => 'yes'], $attributes);
        $this->assertContains(['key' => 'stock_quantity', 'description' => '10'], $attributes);
        $this->assertContains(['key' => 'colour', 'description' => 'red'], $attributes);
    }

    /**
     * Test toAttributesArray returns empty array when all values are null.
     */
    public function test_to_attributes_array_returns_empty_array_when_all_null(): void
    {
        $dto = new ProductAttributeDto(productId: 1);

        $attributes = $dto->toAttributesArray();

        $this->assertCount(0, $attributes);
        $this->assertIsArray($attributes);
    }

    /**
     * Test fromApiData creates DTO from API response.
     */
    public function test_from_api_data_creates_dto_from_api_response(): void
    {
        $apiData = [
            'in_stock' => 'yes',
            'stock_quantity' => '10',
            'condition' => 'new',
            'colour' => 'red',
            'color' => 'blue',
            'gender' => 'unisex',
            'size' => 'M',
            'size_type' => 'regular',
            'custom_1' => 'value1',
            'custom_2' => 'value2',
            'custom_4' => 'value4',
            'custom_5' => 'value5',
            'custom_6' => 'value6',
            'custom_7' => 'value7',
            'custom_8' => 'value8',
            'delivery_weight' => '1.5kg',
            'fashion_suitable_for' => 'adults',
            'fashion_size' => 'medium',
            'merchant_product_category_path' => 'clothing > shirts',
            'product_GTIN' => '1234567890123',
            'installment' => '3x',
        ];

        $dto = ProductAttributeDto::fromApiData(1, $apiData);

        $this->assertEquals(1, $dto->productId);
        $this->assertEquals('yes', $dto->inStock);
        $this->assertEquals('10', $dto->stockQuantity);
        $this->assertEquals('new', $dto->condition);
        $this->assertEquals('red', $dto->colour);
        $this->assertEquals('blue', $dto->color);
    }

    /**
     * Test fromApiData handles missing fields gracefully.
     */
    public function test_from_api_data_handles_missing_fields(): void
    {
        $apiData = [
            'in_stock' => 'yes',
            'condition' => 'new',
        ];

        $dto = ProductAttributeDto::fromApiData(1, $apiData);

        $this->assertEquals(1, $dto->productId);
        $this->assertEquals('yes', $dto->inStock);
        $this->assertEquals('new', $dto->condition);
        $this->assertNull($dto->stockQuantity);
        $this->assertNull($dto->colour);
    }

    /**
     * Test attribute keys are correctly mapped.
     */
    public function test_attribute_keys_are_correctly_mapped(): void
    {
        $dto = new ProductAttributeDto(
            productId: 1,
            custom1: 'value1',
            custom2: 'value2',
            productGTIN: '1234567890123',
            merchantProductCategoryPath: 'path'
        );

        $attributes = $dto->toAttributesArray();

        $keys = array_column($attributes, 'key');
        
        $this->assertContains('custom_1', $keys);
        $this->assertContains('custom_2', $keys);
        $this->assertContains('product_GTIN', $keys);
        $this->assertContains('merchant_product_category_path', $keys);
    }
}
