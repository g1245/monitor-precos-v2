<?php

namespace App\Livewire;

use App\Livewire\Concerns\ScrollsToProductsOnPageChange;
use App\Models\Product;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProducts extends Component
{
    use WithPagination;
    use ScrollsToProductsOnPageChange;

    public string $q = '';

    public string $sortField = 'discount_percentage';
    public string $sortDirection = 'desc';
    public int $perPage = 30;

    // Filter properties
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?string $brand = null;
    public ?int $storeId = null;
    public bool $recentDiscountOnly = false;

    protected $queryString = [
        'q' => ['except' => '', 'as' => 'q'],
        'sortField' => ['except' => 'discount_percentage'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 30],
        'minPrice' => ['except' => null],
        'maxPrice' => ['except' => null],
        'brand' => ['except' => null],
        'storeId' => ['except' => null],
        'recentDiscountOnly' => ['except' => false],
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

    public function updatingRecentDiscountOnly()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->brand = null;
        $this->storeId = null;
        $this->recentDiscountOnly = false;
        $this->resetPage();
    }

    public function render()
    {
        $parsed = $this->parseSearchQuery();

        $query = Product::search($this->q);

        // Apply parsed query filters
        if ($parsed['field'] === 'sku') {
            $query->where('sku', $parsed['value']);
        } elseif ($parsed['field'] === 'name') {
            $query->where('name', $parsed['value']);
        } elseif ($parsed['field'] === 'brand') {
            $query->where('brand', $parsed['value']);
        }

        $products = $query
            ->when($this->minPrice !== null, function ($query) {
                return $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice !== null, function ($query) {
                return $query->where('price', '<=', $this->maxPrice);
            })
            ->when($this->brand !== null && $this->brand !== '', function ($query) {
                return $query->where('brand', $this->brand);
            })
            ->when($this->storeId !== null, function ($query) {
                return $query->where('store_id', $this->storeId);
            })
            ->when($this->recentDiscountOnly, function ($query) {
                return $query->withRecentDiscount(3);
            })
            ->when($this->sortField, function ($query) {
                return $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate($this->perPage);

        $stores = Store::where('has_public', true)->orderBy('name')->get(['id', 'name']);

        return view('livewire.search-products', [
            'products' => $products,
            'searchField' => $parsed['field'],
            'stores' => $stores,
        ]);
    }

    /**
     * Parse a key:value search query into field and value components.
     *
     * Supported syntax:
     *   sku:ABC123
     *   name:"tênis nike"
     *   brand:samsung
     *
     * @return array{field: string|null, value: string}
     */
    private function parseSearchQuery(): array
    {
        $pattern = '/^(sku|name|brand):(?:"([^"]+)"|(\S+))/u';

        if ($this->q && preg_match($pattern, $this->q, $matches)) {
            return [
                'field' => $matches[1],
                'value' => $matches[2] !== '' ? $matches[2] : $matches[3],
            ];
        }

        return ['field' => null, 'value' => $this->q];
    }

    /**
     * Custom pagination view
     */
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
