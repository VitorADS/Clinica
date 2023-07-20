<?php

use App\Controllers\AtendimentoController;
use App\Http\Response;
use App\Models\Repository\AnimalRepository;
use App\Models\Repository\AtendimentoRepository;

$router->get('/atendimento', [
    function($request){
        return new Response(200, AtendimentoController::getHome($request, new AtendimentoRepository()));
    }
]);

$router->get('/atendimento/criar', [
    function($request){
        return new Response(200, AtendimentoController::criarAtendimento($request, new AnimalRepository()));
    }
]);

$router->post('/atendimento/criar', [
    function($request){
        return new Response(200, AtendimentoController::criarAtendimentoAction($request, new AtendimentoRepository()));
    }
]);

$router->get('/atendimento/editar/{id}', [
    function($request, $id){
        return new Response(200, AtendimentoController::editarAtendimento($request, new AtendimentoRepository(), $id));
    }
]);

$router->post('/atendimento/editar/{id}', [
    function($request, $id){
        return new Response(200, AtendimentoController::editarAtendimentoAction($request, new AtendimentoRepository(), $id));
    }
]);

$router->get('/atendimento/remover/{id}', [
    function($request, $id){
        return new Response(200, AtendimentoController::removerAtendimentoAction($request, new AtendimentoRepository(), $id));
    }
]);