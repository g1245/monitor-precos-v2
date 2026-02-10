<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProducts extends Component
{
    use WithPagination;

    public string $q = '';

    public string $sortField = 'name';

    public string $sortDirection = 'asc';

    public int $perPage = 12;

    protected $queryString = [
        'q' => ['except' => '', 'as' => 'q'],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 12],
    ];

    public function mount(string $query = '')
    {
        $this->q = $query;
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->q, function ($query) {
                return $query->search($this->q);
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
