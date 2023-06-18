<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Profissional;
use App\Models\Repository\ProfissionalRepository;
use App\Utils\View;
use Doctrine\ORM\EntityRepository;

class ProfissionalController extends PageController
{
    
    private static function getStatus(Request $request): string
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'created':
                return AlertController::getSuccess('Profissional criada com sucesso!');
                break;
            case 'error':
                return AlertController::getError('Houve algum erro ao tentar criar o Profissional!');
                break;
            case 'deleted':
                return AlertController::getSuccess('Profissional removido com sucesso!');
                break;
            case 'deletederror':
                return AlertController::getSuccess('Houve algum erro ao tentar remover o Profissional!');
                break;
            case 'edited':
                return AlertController::getSuccess('Profissional editado com sucesso!');
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * @param Request $request
     * @param ProfissionalRepository $repository
     * @return string
     */
    public static function getHome(Request $request, ProfissionalRepository $repository): string
    {
        $content = '';
        $profissionais = $repository->findAll();

        foreach($profissionais as $profissional){
            $content .= View::render('profissional/item', [
                'nome' => $profissional->getNome(),
                'email' => $profissional->getEmail(),
                'telefone' => $profissional->getTelefone(),
                'id' => $profissional->getId()
            ]);
        }

        $content = View::render('profissional/profissionais', [
            'profissionais' => $content,
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Profissionais', $content);
    }

    /**
     * @param Request $request
     */
    public static function criarProfissional(Request $request): string
    {
        $content = View::render('profissional/form', [
            'action' => 'Cadastrar',
            'nome' => '',
            'telefone' => '',
            'email' => '',
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Cadastrar Profissional', $content);
    }

    /**
     * @param Request $request
     * @param ProfissionalRepository $repository
     * @return string
     */
    public static function criarProfissionalAction(Request $request, ProfissionalRepository $repository)
    {
        $postVars = $request->getPostVars();

        $profissional = new Profissional();
        $profissional->setNome($postVars['nome']);
        $profissional->setEmail($postVars['email']);
        $profissional->setTelefone($postVars['telefone']);

        $profissional = $repository->save($profissional);

        if($profissional instanceof Profissional && is_numeric($profissional->getId())){
            $request->getRouter()->redirect('/profissional/editar/' . $profissional->getId() . '?status=created');

        } else {
            $request->getRouter()->redirect('/profissional/criar?status=error');
        }
    }

    /**
     * @param Request $request
     * @param ProfissionalRepository $repository
     * @return string
     */
    public static function editarProfissional(Request $request, ProfissionalRepository $repository, int $id): string
    {
        $profissional = $repository->findOneBy(['id' => $id]);

        if(!$profissional instanceof Profissional){
            $request->getRouter()->redirect('/profissional?status=emptyEdit');
            exit;
        }

        $content = '';
        $content .= View::render('profissional/form', [
            'action' => 'Editar',
            'nome' => $profissional->getNome(),
            'telefone' => $profissional->getTelefone(),
            'email' => $profissional->getEmail(),
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Editar Profissional', $content);
    }

    /**
     * @param Request $request
     * @param ProfissionalRepository $repository
     * @return string
     */
    public static function editarProfissionalAction(Request $request, ProfissionalRepository $repository, int $id)
    {
        $profissional = $repository->findOneBy(['id' => $id]);

        if(!$profissional instanceof Profissional){
            $request->getRouter()->redirect('/profissional?status=emptyEdit');
            exit;
        }

        $postVars = $request->getPostVars();

        $profissional->setNome($postVars['nome']);
        $profissional->setTelefone($postVars['telefone']);
        $profissional->setEmail($postVars['email']);

        $repository->save($profissional);
        $request->getRouter()->redirect('/profissional/editar/' . $id . '?status=edited');
    }

    /**
     * @param Request $request
     * @param ProfissionalRepository $repository
     * @return string
     */
    public static function removerProfissionalAction(Request $request, ProfissionalRepository $repository, int $id)
    {
        $return = $repository->remove($id);

        if($return === $id){
            $request->getRouter()->redirect('/profissional?status=deleted');

        } else {
            $request->getRouter()->redirect('/profissional?status=deletederror');
        }
    }
}