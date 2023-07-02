<?php

use App\Controllers\ResponsavelController;
use App\Http\Response;
use App\Models\Repository\ResponsavelRepository;

$router->get('/responsavel', [
    function ($request){
        return new Response(200, ResponsavelController::getHome($request, new ResponsavelRepository()));
    }
]);

$router->get('/responsavel/criar', [
    function ($request){
        return new Response(200, ResponsavelController::criarResponsavel($request, new ResponsavelRepository()));
    }
]);

$router->post('/responsavel/criar', [
    function ($request){
        return new Response(200, ResponsavelController::criarResponsavelAction($request, new ResponsavelRepository()));
    }
]);

$router->get('/responsavel/editar/{id}', [
    function ($request, $id){
        return new Response(200, ResponsavelController::editarResponsavel($request, $id, new ResponsavelRepository()));
    }
]);

$router->post('/responsavel/editar/{id}', [
    function ($request, $id){
        return new Response(200, ResponsavelController::editarResponsavelAction($request, $id, new ResponsavelRepository()));
    }
]);

$router->get('/responsavel/remover/{id}', [
    function ($request, $id){
        return new Response(200, ResponsavelController::removerResponsavelAction($request, $id, new ResponsavelRepository()));
    }
]);