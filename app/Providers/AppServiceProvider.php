<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
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
        // Share department menu data with all views
        View::share('departmentMenu', $this->getDepartmentMenuData());
    }

    /**
     * Get department menu data from database
     */
    private function getDepartmentMenuData()
    {
        try {
            // Get all parent departments with their children
            return \App\Models\Department::whereNull('parent_id')
                ->with(['children' => function($query) {
                    $query->orderBy('name');
                }])
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading department menu: ' . $e->getMessage());
            return collect([]);
        }
    }
}
