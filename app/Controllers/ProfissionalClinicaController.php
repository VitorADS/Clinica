<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Clinica;
use App\Models\Entitys\Profissional;
use App\Models\Entitys\ProfissionalClinica;
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
            case 'emptyClinic':
                return AlertController::getError('Clinica nao encontrada');
                break;
            case 'idProfissionalInvalid':
                return AlertController::getError('Profissional invalido');
                break;
            case 'added':
                return AlertController::getSuccess('Profissional adicionado');
                break;
            case 'removed':
                return AlertController::getSuccess('Profissional removido');
                break;
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
            $content .= View::render('profissionalClinica/item', [
                'nomeProfissional' => $profissionalClinica->getProfissional()->getNome(),
                'emailProfissional' => $profissionalClinica->getProfissional()->getEmail(),
                'telefoneProfissional' => $profissionalClinica->getProfissional()->getTelefone(),
                'idProfissional' => $profissionalClinica->getProfissional()->getId(),
                'idClinica' => $clinica->getId(),
                'buttonaction' => 'listar',
                'buttonclass' => 'danger',
                'buttonvalue' => 'Remover'
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

    /**
     * @param Request $request
     * @param ClinicaRepository $clinicaRepository
     * @param int $idClinica
     * @return string
     */
    public static function cadastrarProfissional(Request $request, ClinicaRepository $clinicaRepository, int $idClinica): string
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
                'idProfissional' => $profissional->getId(),
                'idClinica' => $clinica->getId(),
                'buttonaction' => 'cadastrar',
                'buttonclass' => 'success',
                'buttonvalue' => 'Cadastrar'
            ]);
        }

        $content = View::render('profissionalClinica/cadastrar', [
            'profissionais' => $content,
            'idClinica' => $clinica->getId(),
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Cadastrar Profissional', $content);
    }

    /**
     * @param Request $request
     * @param ProfissionalClinicaRepository $repository
     * @param int $idClinica
     * @return void
     */
    public static function cadastrarProfissionalAction(Request $request, ProfissionalClinicaRepository $repository, int $idClinica): void
    {
        $postVars = $request->getPostVars();

        if(empty($postVars['id_profissional'])){
            $request->getRouter()->redirect('clinic/profissionais/listar/' . $idClinica . '?status=idProfissionalInvalid');
            exit;
        }

        $profissionalClinica = $repository->findOneBy(['clinica' => $idClinica, 'profissional' => (int) $postVars['id_profissional']]);

        if(!$profissionalClinica instanceof ProfissionalClinica){
            $clinicaRepository = new ClinicaRepository();
            $profissionalRepository = new ProfissionalRepository();

            $clinica = $clinicaRepository->findOneBy(['id' => $idClinica]);
            $profissional = $profissionalRepository->findOneBy(['id' => (int) $postVars['id_profissional']]);

            $profissionalClinica = new ProfissionalClinica();
            $profissionalClinica->setClinica($clinica);
            $profissionalClinica->setProfissional($profissional);
            $profissionalClinica = $repository->save($profissionalClinica);

            $request->getRouter()->redirect('/clinic/profissionais/listar/' . $idClinica . '?status=added');
        }
    }

    /**
     * @param Request $request
     * @param ProfissionalClinicaRepository $repository
     * @param int $idClinica
     * @return void
     */
    public static function removerProfissionalAction(Request $request, ProfissionalClinicaRepository $repository, int $idClinica): void
    {
        $postVars = $request->getPostVars();

        if(empty($postVars['id_profissional'])){
            $request->getRouter()->redirect('clinic/profissionais/listar/' . $idClinica . '?status=idProfissionalInvalid');
            exit;
        }

        $profissionalClinica = $repository->findOneBy(['clinica' => $idClinica, 'profissional' => (int) $postVars['id_profissional']]);

        if($profissionalClinica instanceof ProfissionalClinica){
            $profissionalClinica = $repository->remove($profissionalClinica);

            $request->getRouter()->redirect('/clinic/profissionais/listar/' . $idClinica . '?status=removed');
        }
    }
}