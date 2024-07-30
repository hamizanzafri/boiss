<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $roles = explode('|', $role); // Split multiple roles

            // Debug information
            \Log::info('CheckRole middleware', [
                'user_id' => $user->id,
                'user_roles' => $user->roles->pluck('name')->toArray(), // Convert collection to array for better readability
                'required_roles' => $roles,
                'current_route' => $request->route()->getName(), // Log current route name
            ]);

            // Bypass role check for general users
            if ($user->user_type === 'general') {
                \Log::info('CheckRole middleware', ['message' => 'Bypassing role check for general user']);
                return $next($request);
            }

            if ($user->hasAnyRole($roles)) {
                \Log::info('CheckRole middleware', ['message' => 'User has the required role']);
                return $next($request);
            }

            // Handle AJAX request
            if ($request->expectsJson()) {
                \Log::info('CheckRole middleware', ['message' => 'Unauthorized action for AJAX request']);
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            // Handle standard request
            \Log::info('CheckRole middleware', ['message' => 'Unauthorized action for standard request']);
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        \Log::info('CheckRole middleware', ['message' => 'User is not authenticated']);
        return abort(403, 'Unauthorized action.');
    }
}
