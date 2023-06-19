<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Clinica;
use App\Models\Repository\ClinicaRepository;
use App\Models\Repository\ProfissionalClinicaRepository;
use App\Models\Repository\ProfissionalRepository;
use App\Utils\View;
use Doctrine\ORM\EntityRepository;

class ProfissionalClinicaController extends PageController
{

    /**
     * @param Request $request
     * @return string
     */
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
     * @param ClinicaRepository $clinicaRepository
     * @param int $id
     * @return string 
     */
    public static function getHome(Request $request, ClinicaRepository $clinicaRepository, int $id): string
    {
        $clinica = $clinicaRepository->findOneBy(['id' => $id]);
        $content = '';

        if(!$clinica instanceof Clinica){
            $request->getRouter()->redirect('/clinic?status=emptyClinic');
            exit;
        }

        $profissionalClinicaRepository = new ProfissionalClinicaRepository();
        $profissionaisClinica = $profissionalClinicaRepository->findBy(['clinica' => $clinica]);

        foreach($profissionaisClinica as $profissionalClinica){
            $content = View::render('profissionalClinica/item', [
                'nomeProfissional' => $profissionalClinica->getProfissional()->getNome(),
                'telefoneProfissional' => $profissionalClinica->getProfissional()->getTelefone(),
                'emailProfissional' => $profissionalClinica->getProfissional()->getEmail()
            ]);
        }

        $content = View::render('profissionalClinica/index', [
            'id' => $clinica->getId(),
            'nome' => $clinica->getNome(),
            'profissionais' => $content,
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Profissionais - ' . $clinica->getNome(), $content);
    }

    public static function cadastrarProfissional(Request $request, ClinicaRepository $clinicaRepository, int $idClinica)
    {
        $clinica = $clinicaRepository->findOneBy(['id' => $idClinica]);

        if(!$clinica instanceof Clinica){
            $request->getRouter()->redirect('/clinic?status=emptyClinic');
            exit;
        }

        $profissionalRepository = new ProfissionalRepository();
        $profissionais = $profissionalRepository->getProfissionaisCadastraveis($clinica);
        $content = '';

        foreach($profissionais as $profissional){
            $content .= View::render('profissionalClinica/item', [
                'nomeProfissional' => $profissional->getNome(),
                'emailProfissional' => $profissional->getEmail(),
                'telefoneProfissional' => $profissional->getTelefone(),
                'idProfissional' => $profissional->getId()
            ]);
        }
    }
}