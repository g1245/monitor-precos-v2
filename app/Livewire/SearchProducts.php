<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProducts extends Component
{
    use WithPagination;

    public string $q = '';

    public string $sortField = 'updated_at';
    public string $sortDirection = 'desc';
    public int $perPage = 20;

    // Filter properties
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?string $brand = null;
    public ?int $storeId = null;

    protected $queryString = [
        'q' => ['except' => '', 'as' => 'q'],
        'sortField' => ['except' => 'updated_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 20],
        'minPrice' => ['except' => null],
        'maxPrice' => ['except' => null],
        'brand' => ['except' => null],
        'storeId' => ['except' => null],
    ];

    public function mount(string $query = '')
    {
        $this->q = $query;
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

    public function updatingStoreId()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->brand = null;
        $this->storeId = null;
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->q, function ($query) {
                return $query->search($this->q);
            })
            ->when($this->minPrice !== null, function ($query) {
                return $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice !== null, function ($query) {
                return $query->where('price', '<=', $this->maxPrice);
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

        return view('livewire.search-products', [
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
