<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Models\Product;
use App\Models\Department;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

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
        if ($this->app->environment(['production', 'staging'])) {
            URL::forceScheme('https');
        }

        // Register model observers
        Product::observe(ProductObserver::class);
        
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
                    ->with(['children' => function($query) {
                        $query->orderBy('name', 'asc');
                    }])
                    ->orderBy('name', 'asc')
                    ->get();
            });
        } catch (\Exception $e) {
            Log::error('Error loading department menu: ' . $e->getMessage());
            return collect([]);
        }
    }
}
