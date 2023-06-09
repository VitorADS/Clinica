<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Session\Admin\Login as AdminLogin;
use Closure;

class RequireAdminLogin{

    /**
     * @param Request $request
     * @param Closure
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        if(!AdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin/login');
        }

        return $next($request);
    }
}