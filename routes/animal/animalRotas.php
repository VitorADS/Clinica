<?php

use App\Controllers\AnimalController;
use App\Http\Response;
use App\Models\Repository\AnimalRepository;
use App\Models\Repository\RacaRepository;
use App\Models\Repository\TipoRepository;

$router->get('/animal', [
    function ($request){
        return new Response(200, AnimalController::getHome($request, new AnimalRepository()));
    }
]);

$router->get('/animal/criar', [
    function ($request){
        return new Response(200, AnimalController::criarAnimal($request, new TipoRepository(), new RacaRepository()));
    }
]);

$router->post('/animal/criar', [
    function ($request){
        return new Response(200, AnimalController::criarAnimalAction($request, new AnimalRepository()));
    }
]);

$router->get('/animal/editar/{id}', [
    function ($request, $id){
        return new Response(200, AnimalController::editarAnimal($request, $id, new AnimalRepository(), new TipoRepository(), new RacaRepository()));
    }
]);

$router->post('/animal/editar/{id}', [
    function ($request, $id){
        return new Response(200, AnimalController::editarAnimalAction($request, $id, new AnimalRepository()));
    }
]);

$router->get('/animal/remover/{id}', [
    function ($request, $id){
        return new Response(200, AnimalController::removeAnimal($request, $id, new AnimalRepository()));
    }
]);