<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Atendimento;
use App\Models\Entitys\AtendimentoVacina;
use App\Models\Entitys\Vacina;
use App\Models\Repository\AtendimentoRepository;
use App\Models\Repository\AtendimentoVacinaRepository;
use App\Models\Repository\VacinaRepository;

class AtendimentoVacinaController extends PageController
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
     * @param AtendimentoVacinaRepository $repository
     * @return array
     */
    public static function criarAtendimentoVacinaAction(Request $request, AtendimentoVacinaRepository $repository): array
    {
        $postVars = $request->getPostVars();
        $atendimento = (new AtendimentoRepository($repository->getEm()))->find((int) $postVars['atendimento']);

        if(!$atendimento instanceof Atendimento){
            $request->getRouter()->redirect('/atendimento?status=atendimentoNotFound');
        }

        $atendimentoVacina = new AtendimentoVacina();
        $atendimentoVacina->setAtendimento($atendimento);

        return self::saveAtendimentoVacina($request, $repository, $atendimentoVacina, $postVars);
    }

    /**
     * @param Request $request
     * @param AtendimentoVacinaRepository $repository
     * @param AtendimentoVacina $atendimentoVacina
     * @return array
     */
    private static function saveAtendimentoVacina(Request $request, AtendimentoVacinaRepository $repository, AtendimentoVacina $atendimentoVacina, array $postVars): array
    {
        $vacina = (new VacinaRepository($repository->getEm()))->find((int) $postVars['vacina']);

        if(!$vacina instanceof Vacina){
            $request->getRouter()->redirect('/atendimento/editar/' . $atendimentoVacina->getAtendimento()->getId() . '?status=vaccineNotFound');
        }

        $atendimentoVacina->setVacina($vacina);
        $atendimentoVacina = $repository->save($atendimentoVacina);

        if($atendimentoVacina instanceof AtendimentoVacina && $atendimentoVacina->getId()){
            return [
                'success' => true,
                'url' => URL . '/atendimento/editar/' . $atendimentoVacina->getAtendimento()->getId() . '?status=appliedVaccine'
            ];
            $request->getRouter()->redirect('/atendimento/editar/' . $atendimentoVacina->getAtendimento()->getId() . '?status=appliedVaccine');

        } else {
            return [
                'success' => false,
                'url' => URL . '/atendimento/editar/' . $atendimentoVacina->getAtendimento()->getId() . '?status=vaccineError'
            ];
            $request->getRouter()->redirect('/atendimento/editar/' . $atendimentoVacina->getAtendimento()->getId() . '?status=vaccineError');
        }
    }

    /**
     * @param Request $request
     * @param AtendimentoVacinaRepository $repository
     * @param int $id
     * @return void
     */
    public static function removerAction(Request $request, AtendimentoVacinaRepository $repository, int $id): void
    {
        $atendimentoVacina = $repository->find($id);

        if(!$atendimentoVacina instanceof AtendimentoVacina){
            $request->getRouter()->redirect('/atendimento?status=aplicacaoNotFound');
        }

        $idAtendimento = $atendimentoVacina->getAtendimento()->getId();
        $atendimentoVacina = $repository->remove($atendimentoVacina);
        $request->getRouter()->redirect('/atendimento/editar/' . $idAtendimento . '?status=aplicacaoRemoved');
    }
}