<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryProducts extends Component
{
    use WithPagination;

    public string $category;
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 12;
    
    // Filter properties
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?float $minPriceRegular = null;
    public ?float $maxPriceRegular = null;
    public ?string $brand = null;
    public ?int $storeId = null;

    protected $queryString = [
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 12],
        'minPrice' => ['except' => null],
        'maxPrice' => ['except' => null],
        'minPriceRegular' => ['except' => null],
        'maxPriceRegular' => ['except' => null],
        'brand' => ['except' => null],
        'storeId' => ['except' => null],
    ];

    public function mount(string $category)
    {
        $this->category = $category;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function updatingMinPriceRegular()
    {
        $this->resetPage();
    }

    public function updatingMaxPriceRegular()
    {
        $this->resetPage();
    }

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function updatingStoreId()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->minPriceRegular = null;
        $this->maxPriceRegular = null;
        $this->brand = null;
        $this->storeId = null;
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->minPrice !== null, function ($query) {
                return $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice !== null, function ($query) {
                return $query->where('price', '<=', $this->maxPrice);
            })
            ->when($this->minPriceRegular !== null, function ($query) {
                return $query->where('price_regular', '>=', $this->minPriceRegular);
            })
            ->when($this->maxPriceRegular !== null, function ($query) {
                return $query->where('price_regular', '<=', $this->maxPriceRegular);
            })
            ->when($this->brand !== null && $this->brand !== '', function ($query) {
                return $query->where('brand', 'LIKE', "%{$this->brand}%");
            })
            ->when($this->storeId !== null, function ($query) {
                return $query->where('store_id', $this->storeId);
            })
            ->when($this->sortField, function ($query) {
                return $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate($this->perPage);

        return view('livewire.category-products', [
            'products' => $products,
        ]);
    }
    
    /**
     * Custom pagination view
     */
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
