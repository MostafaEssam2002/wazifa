<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckAdminStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has the status of 'admin'
        if (Auth::check() && Auth::user()->status === 'admin') {
            return $next($request);
        }
        // If not admin, redirect or abort with an error
        return redirect('/')->with('error', 'Access denied! Admins only.');
    }
}
