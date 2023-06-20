<?php

use App\Controllers\AtendimentoController;
use App\Http\Response;

$router->get('/atendimento', [
    function($request){
        return new Response(200, AtendimentoController::getHome($request));
    }
]);