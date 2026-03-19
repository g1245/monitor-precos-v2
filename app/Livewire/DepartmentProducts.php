<?php

namespace App\Livewire;

use App\Livewire\Concerns\ScrollsToProductsOnPageChange;
use App\Models\Department;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentProducts extends Component
{
    use WithPagination;
    use ScrollsToProductsOnPageChange;

    public Department $department;
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
        'sortField' => ['except' => 'discount_percentage'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 30],
        'minPrice' => ['except' => null],
        'maxPrice' => ['except' => null],
        'brand' => ['except' => null],
        'storeId' => ['except' => null],
        'recentDiscountOnly' => ['except' => false],
    ];

    public function mount(Department $department)
    {
        $this->department = $department;
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
        $departmentIds = [$this->department->id];

        if ($this->department->hasChildren()) {
            $departmentIds = array_merge($departmentIds, $this->department->getAllDescendantIds());
        }

        $products = Product::query()
            ->where('is_parent', 0)
            ->whereHas('departments', function ($query) use ($departmentIds) {
                $query->whereIn('departments.id', $departmentIds);
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
            ->when($this->recentDiscountOnly, function ($query) {
                return $query->withRecentPriceChange(3);
            })
            ->when($this->sortField, function ($query) {
                return $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate($this->perPage);

        return view('livewire.department-products', [
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