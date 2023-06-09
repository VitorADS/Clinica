<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Session\Admin\Login as AdminLogin;
use Closure;

class RequireAdminLogout{

    /**
     * @param Request $request
     * @param Closure
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        if(AdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin');
        }

        return $next($request);
    }
}