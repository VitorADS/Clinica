<?php

namespace App\routes;

use App\Controllers\Pages\HomeController;
use App\Http\Response;

$router->get('/', [
    function($request){
        return new Response(200, HomeController::getHome($request));
    }
]);

include __DIR__ . '/Clinica/clinicaRotas.php';
include __DIR__ . '/Profissional/profissionalRotas.php';
include __DIR__ . '/profissionalClinica/rotasPC.php';
include __DIR__ . '/Atendimento/rotasAtendimento.php';