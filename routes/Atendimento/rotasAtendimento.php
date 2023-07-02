<?php

use App\Controllers\AtendimentoController;
use App\Http\Response;
use App\Models\Repository\AtendimentoRepository;
use App\Models\Repository\ClinicaRepository;

$router->get('/atendimento', [
    function($request){
        return new Response(200, AtendimentoController::getHome($request, new AtendimentoRepository()));
    }
]);

$router->get('/atendimento/criar', [
    function($request){
        return new Response(200, AtendimentoController::criarAtendimento($request, new AtendimentoRepository(), new ClinicaRepository()));
    }
]);