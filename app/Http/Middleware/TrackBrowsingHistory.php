<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserBrowsingHistory;
use Illuminate\Support\Facades\Auth;

class TrackBrowsingHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests
        if ($request->isMethod('GET') && $response->isSuccessful()) {
            $this->trackVisit($request);
        }

        return $response;
    }

    /**
     * Track the page visit.
     */
    protected function trackVisit(Request $request): void
    {
        $route = $request->route();
        if (!$route) {
            return;
        }

        $routeName = $route->getName();
        $pageType = $this->determinePageType($routeName);

        if (!$pageType) {
            return; // Don't track certain pages like assets, etc.
        }

        $data = [
            'user_id' => Auth::id(),
            'page_type' => $pageType,
            'page_url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ];

        // Add specific IDs based on route
        if ($routeName === 'product.show') {
            $data['product_id'] = $route->parameter('id');
        } elseif ($routeName === 'department.index') {
            $data['department_id'] = $route->parameter('departmentId');
        } elseif ($routeName === 'store.show') {
            $data['store_id'] = $route->parameter('id');
        }

        UserBrowsingHistory::create($data);
    }

    /**
     * Determine the page type based on route name.
     */
    protected function determinePageType(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }

        return match (true) {
            $routeName === 'welcome' => 'home',
            str_starts_with($routeName, 'product.') => 'product',
            str_starts_with($routeName, 'department.') => 'department',
            str_starts_with($routeName, 'store.') => 'store',
            str_starts_with($routeName, 'search.') => 'search',
            default => null,
        };
    }
}
