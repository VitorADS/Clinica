<?php

use App\Controllers\ResponsavelAnimalController;
use App\Http\Response;
use App\Models\Repository\AnimalRepository;
use App\Models\Repository\ResponsavelAnimalRepository;

$router->get('/animal/responsavel/listar/{id}', [
    function ($request, $id){
        return new Response(200, ResponsavelAnimalController::getHome($request, new AnimalRepository(), $id));
    }
]);

$router->get('/animal/responsavel/adicionar/{id}', [
    function ($request, $id){
        return new Response(200, ResponsavelAnimalController::adicionarResponsavel($request, new AnimalRepository(), $id));
    }
]);

$router->post('/animal/responsavel/adicionar/{id}', [
    function ($request, $id){
        return new Response(200, ResponsavelAnimalController::adicionarResponsavelAction($request, new AnimalRepository(), $id));
    }
]);

$router->get('/animal/responsavel/padrao', [
    function ($request){
        return new Response(200, ResponsavelAnimalController::setPadrao($request, new ResponsavelAnimalRepository()));
    }
]);

$router->get('/animal/responsavel/remover', [
    function ($request){
        return new Response(200, ResponsavelAnimalController::removerResponsavelAnimal($request, new ResponsavelAnimalRepository()));
    }
]);