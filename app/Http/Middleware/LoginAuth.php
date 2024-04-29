<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAuth
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
        if (auth()->guard('web')->check()) {
            $userRole = auth()->guard('web')->user()->isAdmin;
            //dd($userRole);
            if (in_array($userRole, [1, 2])) {
                if ($request->is('emp/enrollment', 'emp/scheduler', 'emp/assessment', 'emp/cashiering', 'emp/scholarship', 'emp/grades', 'emp/request') || $request->is('emp/enrollment/*', 'emp/scheduler/*', 'emp/assessment/*', 'emp/cashiering/*', 'emp/scholarship/*', 'emp/grades/*')) {
                    return redirect()->route('home')->with('error', 'You have no permission to access this page');
                }
            }
            if (in_array($userRole, [3, 4])) {
                if ($request->is('emp/admission', 'emp/scheduler', 'emp/assessment', 'emp/cashiering', 'emp/scholarship', 'emp/grades', 'emp/request') || $request->is('emp/admission/*', 'emp/scheduler/*', 'emp/assessment/*', 'emp/cashiering/*', 'emp/scholarship/*', 'emp/grades/*')) {
                    return redirect()->route('home')->with('error', 'You have no permission to access this page');
                }
            }
            if (in_array($userRole, [5, 6, 7])) {
                if ($request->is('emp/enrollment', 'emp/assessment', 'emp/cashiering', 'emp/scholarship', 'emp/grades', 'emp/request') || $request->is('emp/enrollment/*', 'emp/assessment/*', 'emp/cashiering/*', 'emp/scholarship/*', 'emp/grades/*')) {
                    return redirect()->route('home')->with('error', 'You have no permission to access this page');
                }
            }
            if (in_array($userRole, [8, 9])) {
                if ($request->is('emp/admission', 'emp/enrollment', 'emp/scheduler', 'emp/assessment', 'emp/cashiering', 'emp/grades', 'emp/request') || $request->is('emp/admission/*', 'emp/enrollment/*', 'emp/scheduler/*', 'emp/assessment/*', 'emp/cashiering/*', 'emp/grades/*')) {
                    return redirect()->route('home')->with('error', 'You have no permission to access this page');
                }
            }
            if (in_array($userRole, [10, 11])) {
                if ($request->is('emp/admission', 'emp/enrollment', 'emp/scheduler', 'emp/scholarship', 'emp/cashiering', 'emp/grades', 'emp/request') || $request->is('emp/admission/*', 'emp/enrollment/*', 'emp/scheduler/*', 'emp/scholarship/*', 'emp/cashiering/*', 'emp/grades/*')) {
                    return redirect()->route('home')->with('error', 'You have no permission to access this page');
                }
            }
            if (in_array($userRole, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])) {
                if ($request->is('emp/settings/usersAccount/list/all/users')) {
                    return redirect()->route('home')->with('error', 'You have no permission to access this page');
                }
            }
        } elseif (auth()->guard('faculty')->check()) {
            if ($request->is('emp/admission') || $request->is('emp/admission/*') || $request->is('emp/enrollment') || $request->is('emp/enrollment/*')) {
                return redirect()->route('home')->with('error', 'No permission to access this page');
            }
        }else {
            return redirect()->route('login')->with('error', 'You have to sign in first to access this page');
        }
        
        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }
}
