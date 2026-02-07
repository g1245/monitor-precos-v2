<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class DepartmentProducts extends Component
{
    use WithPagination;

    public Department $department;
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 12;

    protected $queryString = [
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 12]
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

    public function render()
    {
        $departmentIds = [$this->department->id];

        if ($this->department->hasChildren()) {
            $departmentIds = array_merge($departmentIds, $this->department->getAllDescendantIds());
        }

        $products = Product::whereHas('departments', function ($query) use ($departmentIds) {
            $query->whereIn('departments.id', $departmentIds);
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