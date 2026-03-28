<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class CategoryProducts extends Component
{
    public string $category;
    public string $sortField = 'discount_percentage';
    public string $sortDirection = 'desc';
    public int $page = 1;
    public bool $hasMore = true;

    // Filter properties
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?string $brand = null;
    public ?int $storeId = null;
    public bool $recentDiscountOnly = false;

    protected $queryString = [
        'sortField'          => ['except' => 'discount_percentage'],
        'sortDirection'      => ['except' => 'desc'],
        'page'               => ['except' => 1],
        'minPrice'           => ['except' => null],
        'maxPrice'           => ['except' => null],
        'brand'              => ['except' => null],
        'storeId'            => ['except' => null],
        'recentDiscountOnly' => ['except' => false],
    ];

    public function mount(string $category): void
    {
        $this->category = $category;
    }

    public function loadMore(): void
    {
        $this->page++;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->page = 1;
    }

    public function updatingSortField(): void
    {
        $this->page = 1;
    }

    public function updatingSortDirection(): void
    {
        $this->page = 1;
    }

    public function updatingMinPrice(): void
    {
        $this->page = 1;
    }

    public function updatingMaxPrice(): void
    {
        $this->page = 1;
    }

    public function updatingBrand(): void
    {
        $this->page = 1;
    }

    public function updatingStoreId(): void
    {
        $this->page = 1;
    }

    public function updatingRecentDiscountOnly(): void
    {
        $this->page = 1;
    }

    public function clearFilters(): void
    {
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->brand = null;
        $this->storeId = null;
        $this->recentDiscountOnly = false;
        $this->page = 1;
    }

    public function render()
    {
        $limit = $this->page * 30;

        $query = Product::query()
            ->where('is_parent', 0)
            ->when($this->minPrice !== null, fn ($q) => $q->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice !== null, fn ($q) => $q->where('price', '<=', $this->maxPrice))
            ->when($this->brand !== null && $this->brand !== '', fn ($q) => $q->where('brand', 'LIKE', "%{$this->brand}%"))
            ->when($this->storeId !== null, fn ($q) => $q->where('store_id', $this->storeId))
            ->when($this->recentDiscountOnly, fn ($q) => $q->withRecentPriceChange(3))
            ->orderBy($this->sortField, $this->sortDirection);

        $total = (clone $query)->count();
        $products = $query->limit($limit + 1)->get();

        $this->hasMore = $products->count() > $limit;
        $products = $products->take($limit);

        return view('livewire.category-products', [
            'products' => $products,
            'total'    => $total,
        ]);
    }
}
