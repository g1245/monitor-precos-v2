<?php

namespace App\Http\Middleware;

use App\Models\UserVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track for authenticated users and GET requests
        if (Auth::check() && $request->isMethod('GET')) {
            $this->trackVisit($request);
        }

        return $response;
    }

    /**
     * Track the user visit.
     */
    protected function trackVisit(Request $request): void
    {
        // Get the route name to determine what to track
        $routeName = $request->route()->getName();
        
        if (in_array($routeName, ['product.show', 'department.index'])) {
            $visitableType = null;
            $visitableId = null;

            if ($routeName === 'product.show') {
                $visitableType = \App\Models\Product::class;
                $visitableId = $request->route('productId');
            } elseif ($routeName === 'department.index') {
                $visitableType = \App\Models\Department::class;
                $visitableId = $request->route('departmentId');
            }

            if ($visitableType && $visitableId) {
                UserVisit::create([
                    'user_id' => Auth::id(),
                    'visitable_type' => $visitableType,
                    'visitable_id' => $visitableId,
                    'url' => $request->fullUrl(),
                ]);
            }
        }
    }
}
