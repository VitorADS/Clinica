<?php

namespace App\routes;

use App\Controllers\Pages\HomeController;
use App\Http\Response;

$router->get('/', [
    function(){
        return new Response(200, HomeController::getHome());
    },
    //'entity' => 'User'
]);