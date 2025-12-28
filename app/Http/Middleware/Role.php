<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
//        dd($role);
        if (!$request->user() || $request->user()->role !== $role) {
            return redirect()->route($this->getDashboardRoute($request->user()->role));
        }
        return $next($request);
    }

    private function getDashboardRoute(string $role): string
    {
        return match ($role) {
            'admin' => 'admin.dashboard',
            'hotel_owner' => 'hotelOwner.dashboard',
            'user' => 'user.dashboard',
            default => 'login',
        };
    }
}
