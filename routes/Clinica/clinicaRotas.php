<?php

use App\Controllers\ClinicaController;
use App\Http\Response;
use App\Models\Entitys\Clinica;
use App\Models\Repository\ClinicaRepository;

$router->get('/clinic', [
    function($request){
        return new Response(200, ClinicaController::getHome($request, new ClinicaRepository(Clinica::class)));
    }
]);

$router->get('/clinic/criar', [
    function($request){
        return new Response(200, ClinicaController::criarClinica($request));
    }
]);

$router->post('/clinic/criar', [
    function($request){
        return new Response(200, ClinicaController::criarClinicaAction($request, new ClinicaRepository(Clinica::class)));
    }
]);

$router->get('/clinic/editar/{id}', [
    function($request, $id){
        return new Response(200, ClinicaController::editarClinica($request, new ClinicaRepository(Clinica::class), $id));
    }
]);

$router->post('/clinic/editar/{id}', [
    function($request, $id){
        return new Response(200, ClinicaController::editarClinicaAction($request, new ClinicaRepository(Clinica::class), $id));
    }
]);

$router->get('/clinic/remover/{id}', [
    function($request, $id){
        return new Response(200, ClinicaController::removerClinicaAction($request, new ClinicaRepository(Clinica::class), $id));
    }
]);

$router->get('/clinic/profissionais/{id}', [
    function($request){
        return new Response(200, ClinicaController::criarClinica($request));
    }
]);