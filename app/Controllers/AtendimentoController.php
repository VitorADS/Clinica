<?php

namespace App\Controllers;

use App\Controllers\AlertController;
use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Repository\AtendimentoRepository;
use App\Models\Repository\ClinicaRepository;
use App\Utils\View;

class AtendimentoController extends PageController
{

    private static function getStatus(Request $request): string
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            default:
                return '';
                break;
        }
    }

    /**
     * @param Request $request
     * @param AtendimentoRepository $repository
     * @return string
     */
    public static function getHome(Request $request, AtendimentoRepository $repository): string
    {
        $content = '';
        $atendimentos = $repository->findAll();

        foreach($atendimentos as $atendimento){
            $content .= View::render('atendimento/itemAtendimento', [
                'clinica' => $atendimento->getClinica()->getNome(),
                'profissional' => $atendimento->getProfissionalClinica()->getProfissional()->getNome(),
                'responsavelAnimal' => $atendimento->getAnimal()
            ]);
        }

        $content = View::render('atendimento/home', [
            'atendimentos' => $content,
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Atendimentos', $content);
    }

    /**
     * @param Request $request
     * @param AtendimentoRepository $atendimentoRepository
     * @param ClinicaRepository $clinicaRepository
     * @return string
     */
    public static function criarAtendimento(Request $request, AtendimentoRepository $atendimentoRepository, ClinicaRepository $clinicaRepository): string
    {
        $content = '';
        $clinicas = $clinicaRepository->findAll();

        foreach($clinicas as $clinica){
            $content .= View::render('atendimento/clinicasItem', [
                'id' => $clinica->getId(),
                'nome' => $clinica->getNome()
            ]);
        }

        $content = View::render('atendimento/formAtendimento', [
            'options' => $content,
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Criar Atendimento', $content);
    }
}