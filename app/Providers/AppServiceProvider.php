<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share department menu data with all views
        View::share('departmentMenu', $this->getDepartmentMenuData());

        // Registrar componentes Livewire manualmente
        Livewire::component('department-products', \App\Livewire\DepartmentProducts::class);

        // Use Tailwind para paginação
        Paginator::defaultView('pagination::tailwind');
    }

    /**
     * Get department menu data from database with 5-minute caching
     *
     * @return \Illuminate\Support\Collection
     */
    private function getDepartmentMenuData()
    {
        try {
            // Cache department menu data for 300 seconds (5 minutes)
            return Cache::remember('department_menu', 300, function () {
                // Get all parent departments with their children
                return Department::whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->orderBy('name', 'asc');
                    }])
                    ->orderBy('name', 'asc')
                    ->get();
            });
        } catch (\Exception $e) {
            Log::error('Error loading department menu: '.$e->getMessage());

            return collect([]);
        }
    }
}
