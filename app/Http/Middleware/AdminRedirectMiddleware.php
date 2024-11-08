<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRedirectMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && in_array(Auth::user()->role_id, [Role::ADMIN, Role::OWNER])) {
            if ($request->path() == '/') {
                return redirect(URL_ADMIN_DASHBOARD);
            }
        }

        return $next($request);
    }
}
