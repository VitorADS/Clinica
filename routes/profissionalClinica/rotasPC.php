<?php

use App\Controllers\ProfissionalClinicaController;
use App\Http\Response;
use App\Models\Repository\ClinicaRepository;
use App\Models\Repository\ProfissionalClinicaRepository;

$router->get('/clinic/profissionais/listar/{id}', [
    function($request, $id){
        return new Response(200, ProfissionalClinicaController::getHome($request, new ClinicaRepository(), $id));
    }
]);

$router->post('/clinic/profissionais/listar/{id}', [
    function($request, $id){
        return new Response(200, ProfissionalClinicaController::removerProfissionalAction($request, new ProfissionalClinicaRepository(), $id));
    }
]);

$router->get('/clinic/profissionais/cadastrar/{id}', [
    function($request, $id){
        return new Response(200, ProfissionalClinicaController::cadastrarProfissional($request, new ClinicaRepository(), $id));
    }
]);

$router->post('/clinic/profissionais/cadastrar/{id}', [
    function($request, $id){
        return new Response(200, ProfissionalClinicaController::cadastrarProfissionalAction($request, new ProfissionalClinicaRepository(), $id));
    }
]);