<?php

namespace App\Http\Middleware;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class InertiaGlobalMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        Inertia::share([
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);

        return $next($request);
    }
}
