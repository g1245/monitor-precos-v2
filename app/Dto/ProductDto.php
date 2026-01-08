<?php

namespace App\Dto;

class ProductDto
{
    public string $name;
    public ?string $permalink;
    public ?string $description;
    public ?float $price;
    public ?float $regular_price;
    public ?string $sku;
    public ?string $brand;
    public ?string $image_url;
    public bool $is_active;
    public ?array $vector_search;

    /**
     * Convert DTO to array for mass assignment.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'permalink' => $this->permalink,
            'description' => $this->description,
            'price' => $this->price,
            'regular_price' => $this->regular_price,
            'sku' => $this->sku,
            'brand' => $this->brand,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
            'vector_search' => $this->vector_search,
        ];
    }
}