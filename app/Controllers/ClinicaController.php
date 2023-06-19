<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Clinica;
use App\Models\Repository\ClinicaRepository;
use App\Utils\View;
use Doctrine\ORM\EntityRepository;

class ClinicaController extends PageController
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

    /**
     * @param Request $request
     * @param ClinicaRepository $repository
     * @return string
     */
    public static function getHome(Request $request, ClinicaRepository $repository) : string
    {
        $clinicas = $repository->findAll();
        $content = '';

        foreach($clinicas as $clinica){
            $content .= View::render('clinica/item', [
                'nome' => $clinica->getNome(),
                'email' => $clinica->getEmail(),
                'telefone' => $clinica->getTelefone(),
                'id' => $clinica->getId()
            ]);
        }

        $content = View::render('clinica/clinicas', [
            'clinicas' => $content,
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Clinica', $content);
    }

    /**
     * @param Request $request
     */
    public static function criarClinica(Request $request): string
    {
        $content = View::render('clinica/form', [
            'action' => 'Cadastrar',
            'nome' => '',
            'telefone' => '',
            'email' => '',
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Cadastrar Clinica', $content);
    }

    /**
     * @param Request $request
     * @param ClinicaRepository $repository
     * @return string
     */
    public static function criarClinicaAction(Request $request, ClinicaRepository $repository)
    {
        $postVars = $request->getPostVars();

        $clinica = new Clinica();
        $clinica->setNome($postVars['nome']);
        $clinica->setEmail($postVars['email']);
        $clinica->setTelefone($postVars['telefone']);

        $clinica = $repository->save($clinica);

        if($clinica instanceof Clinica && is_numeric($clinica->getId())){
            $request->getRouter()->redirect('/clinic/editar/' . $clinica->getId() . '?status=created');

        } else {
            $request->getRouter()->redirect('/clinic/criar?status=error');
        }
    }

    /**
     * @param Request $request
     * @param ClinicaRepository $repository
     * @return string
     */
    public static function editarClinica(Request $request, ClinicaRepository $repository, int $id): string
    {
        $clinica = $repository->findOneBy(['id' => $id]);

        if(!$clinica instanceof Clinica){
            $request->getRouter()->redirect('/clinic?status=emptyEdit');
            exit;
        }

        $content = '';
        $content .= View::render('clinica/form', [
            'action' => 'Editar',
            'nome' => $clinica->getNome(),
            'telefone' => $clinica->getTelefone(),
            'email' => $clinica->getEmail(),
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Editar Clinica', $content);
    }

    /**
     * @param Request $request
     * @param ClinicaRepository $repository
     * @return string
     */
    public static function editarClinicaAction(Request $request, ClinicaRepository $repository, int $id)
    {
        $clinica = $repository->findOneBy(['id' => $id]);

        if(!$clinica instanceof Clinica){
            $request->getRouter()->redirect('/clinic?status=emptyEdit');
            exit;
        }

        $postVars = $request->getPostVars();

        $clinica->setNome($postVars['nome']);
        $clinica->setTelefone($postVars['telefone']);
        $clinica->setEmail($postVars['email']);

        $repository->save($clinica);
        $request->getRouter()->redirect('/clinic/editar/' . $id . '?status=edited');
    }

    /**
     * @param Request $request
     * @param ClinicaRepository $repository
     * @return string
     */
    public static function removerClinicaAction(Request $request, ClinicaRepository $repository, int $id)
    {
        $return = $repository->remove($id);

        if($return === $id){
            $request->getRouter()->redirect('/clinic?status=deleted');

        } else {
            $request->getRouter()->redirect('/clinic?status=deletederror');
        }
    }
}

?>