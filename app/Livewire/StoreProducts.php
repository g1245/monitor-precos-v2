<?php

namespace App\Livewire;

use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class StoreProducts extends Component
{
    use WithPagination;

    public Store $store;

    public string $sortField = 'name';

    public string $sortDirection = 'asc';

    public int $perPage = 50;

    protected $queryString = [
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 50],
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

    public function render()
    {
        $products = $this->store->products()
            ->where('is_active', true)
            ->when($this->sortField, function ($query) {
                return $query->orderBy($this->sortField, $this->sortDirection);
            })
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
