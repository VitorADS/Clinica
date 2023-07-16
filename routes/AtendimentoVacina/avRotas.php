<?php

use App\Http\Response;
use App\Controllers\AtendimentoVacinaController;
use App\Models\Repository\AtendimentoVacinaRepository;

$router->post('/atendimentovacina/criar', [
    'middlewares' => [
        'api'
    ],
    function($request){
        return new Response(200, AtendimentoVacinaController::criarAtendimentoVacinaAction($request, new AtendimentoVacinaRepository()), 'application/json');
    }
]);

$router->get('/atendimentovacina/remover/{id}', [
    function($request, $id){
        return new Response(200, AtendimentoVacinaController::removerAction($request, new AtendimentoVacinaRepository(), $id));
    }
]);