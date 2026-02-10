<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class StoreProducts extends Component
{
    use WithPagination;

    public Store $store;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 20;
    
    // Filter properties
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?string $brand = null;

    protected $queryString = [
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 20],
        'minPrice' => ['except' => null],
        'maxPrice' => ['except' => null],
        'brand' => ['except' => null],
    ];

    public function mount(Store $store)
    {
        $this->store = $store;
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

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->brand = null;
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::where('store_id', $this->store->id)
            ->when($this->minPrice !== null, function ($query) {
                return $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice !== null, function ($query) {
                return $query->where('price', '<=', $this->maxPrice);
            })
            ->when($this->brand !== null && $this->brand !== '', function ($query) {
                return $query->where('brand', 'LIKE', "%{$this->brand}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.store-products', [
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
