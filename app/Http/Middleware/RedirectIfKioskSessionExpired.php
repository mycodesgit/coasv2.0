<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfKioskSessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('kioskstudent')->check()) {
            if (!$request->session()->has('last_activity_time')) {
                $request->session()->put('last_activity_time', time());
            } elseif (time() - $request->session()->get('last_activity_time') > config('session.lifetime') * 60) {
                Auth::guard('kioskstudent')->logout();
                return redirect()->route('loginkioskstud')->with('error', 'Your session has expired. Please log in again.');
            }
            $request->session()->put('last_activity_time', time());
        }
        return $next($request);
    }
}
