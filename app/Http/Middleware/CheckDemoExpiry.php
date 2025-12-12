<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDemoExpiry
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->isDemo() && $user->isDemoExpired()) {
            // Clear user's demo data
            $user->categories()->delete();
            $user->products()->forceDelete();

            // Logout the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/demo')->with('error', 'Your demo session has expired. Please start a new demo.');
        }

        return $next($request);
    }
}
