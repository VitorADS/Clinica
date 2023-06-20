<?php

namespace App\Controllers;

use App\Controllers\AlertController;
use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Utils\View;

class AtendimentoController extends PageController
{

    private static function getStatus(Request $request): string
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'created':
                return AlertController::getSuccess('Clinica criada com sucesso!');
                break;
            case 'error':
                return AlertController::getError('Houve algum erro ao tentar criar a clinica!');
                break;
            case 'deleted':
                return AlertController::getSuccess('Clinica removida com sucesso!');
                break;
            case 'deletederror':
                return AlertController::getSuccess('Houve algum erro ao tentar remover a clinica!');
                break;
            case 'emptyEdit':
                return AlertController::getError('Esta clinica nao existe!');
                break;
            case 'edited':
                return AlertController::getSuccess('Clinica editada com sucesso!');
                break;
            case 'emptyClinic':
                return AlertController::getError('Clinica nao encontrada');
                break;
            default:
                return '';
                break;
        }
    }

    public static function getHome(): string
    {
        $content = '';

        $content = View::render('atendimento/home', [

        ]);

        return parent::getPage('Atendimentos', $content);
    }
}