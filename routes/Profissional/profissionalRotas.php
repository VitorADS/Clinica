<?php

use App\Controllers\ProfissionalController;
use App\Http\Response;
use App\Models\Entitys\Profissional;
use App\Models\Repository\ProfissionalRepository;

$router->get('/profissional', [
    function ($request){
        return new Response(200, ProfissionalController::getHome($request, new ProfissionalRepository(Profissional::class)));
    }
]);

$router->get('/profissional/criar', [
    function ($request){
        return new Response(200, ProfissionalController::criarProfissional($request));
    }
]);

$router->post('/profissional/criar', [
    function ($request){
        return new Response(200, ProfissionalController::criarProfissionalAction($request, new ProfissionalRepository(Profissional::class)));
    }
]);

$router->get('/profissional/editar/{id}', [
    function ($request, $id){
        return new Response(200, ProfissionalController::editarProfissional($request, new ProfissionalRepository(Profissional::class), $id));
    }
]);

$router->post('/profissional/editar/{id}', [
    function ($request, $id){
        return new Response(200, ProfissionalController::editarProfissionalAction($request, new ProfissionalRepository(Profissional::class), $id));
    }
]);

$router->get('/profissional/remover/{id}', [
    function ($request, $id){
        return new Response(200, ProfissionalController::removerProfissionalAction($request, new ProfissionalRepository(Profissional::class), $id));
    }
]);