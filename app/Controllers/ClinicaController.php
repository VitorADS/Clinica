<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Clinica;
use App\Models\Repository\ClinicaRepository;
use App\Utils\View;
use Doctrine\ORM\EntityRepository;

class ClinicaController extends PageController{

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
            default:
                return '';
                break;
        }
    }

    /**
     * @param Request $request
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
        $content = View::render('clinica/criarClinica', [
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Cadastrar Clinica', $content);
    }

    /**
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
            $request->getRouter()->redirect('/clinic/criar?status=created');

        } else {
            $request->getRouter()->redirect('/clinic/criar?status=error');
        }
    }

        /**
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