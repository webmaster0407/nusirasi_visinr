<?php

namespace App\Http\Middleware;

use Closure;

class AllowOnlyAdminByIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!in_array($request->ip(), Config('app_config.admin_ip'))) {
            // TODO: redirect to default/main route
            die('404');
        }

        return $next($request);
    }
}
