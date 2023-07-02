<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Responsavel;
use App\Models\Repository\ResponsavelRepository;
use App\Utils\View;

class ResponsavelController extends PageController
{
    /**
     * @param Request $request
     * @return string
     */
    public static function getStatus(Request $request): string
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'created':
                return AlertController::getSuccess('Responsavel criado!');
                break;  
            case 'emptyEdit':
                return AlertController::getError('Responsavel nao encontrado!');
                break;   
            case 'edited':
                return AlertController::getSuccess('Responsavel editado!');
                break;      
            case 'deleted':
                return AlertController::getSuccess('Responsavel removido!');
                break; 
            case 'deletederror':
                return AlertController::getSuccess('Erro ao remover responsavel!');
                break;         
            default:
                return '';
                break;
        }
    }

    /**
     * @param Request $request
     * @param ResponsavelRepository $repository
     * @return string
     */
    public static function getHome(Request $request, ResponsavelRepository $repository): string
    {
        $content = '';
        $responsaveis = $repository->findAll();

        foreach($responsaveis as $responsavel){
            $content .= View::render('responsavel/responsavel', [
                'id' => $responsavel->getId(),
                'nome' => $responsavel->getNome(),
                'email' => $responsavel->getEmail(),
                'telefone' => $responsavel->getTelefone()
            ]);
        }

        $content = View::render('responsavel/index', [
            'status' => self::getStatus($request),
            'responsavel' => $content
        ]);

        return parent::getPage('Responsavel', $content);
    }

    /**
     * @param Request $request
     */
    public static function criarResponsavel(Request $request): string
    {
        $content = View::render('responsavel/form', [
            'action' => 'Cadastrar',
            'nome' => '',
            'telefone' => '',
            'email' => '',
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Cadastrar Responsavel', $content);
    }

    /**
     * @param Request $request
     * @param ResponsavelRepository $repository
     * @return string
     */
    public static function criarResponsavelAction(Request $request, ResponsavelRepository $repository)
    {
        $postVars = $request->getPostVars();

        $responsavel = new Responsavel();
        $responsavel->setNome($postVars['nome']);
        $responsavel->setEmail($postVars['email']);
        $responsavel->setTelefone($postVars['telefone']);

        $responsavel = $repository->save($responsavel);

        if($responsavel instanceof Responsavel && is_numeric($responsavel->getId())){
            $request->getRouter()->redirect('/responsavel/editar/' . $responsavel->getId() . '?status=created');

        } else {
            $request->getRouter()->redirect('/responsavel/criar?status=error');
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @param ResponsavelRepository $repository
     */
    public static function editarResponsavel(Request $request, int $id, ResponsavelRepository $repository): string
    {
        $responsavel = $repository->find($id);

        if(!$responsavel instanceof Responsavel){
            $request->getRouter()->redirect('/responsavel?status=emptyEdit');
            exit;
        }

        $content = View::render('responsavel/form', [
            'action' => 'Editar',
            'nome' => $responsavel->getNome(),
            'telefone' => $responsavel->getTelefone(),
            'email' => $responsavel->getEmail(),
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Editar Responsavel', $content);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param ResponsavelRepository $repository
     * @return string
     */
    public static function editarResponsavelAction(Request $request, int $id, ResponsavelRepository $repository)
    {
        $postVars = $request->getPostVars();

        $responsavel = new Responsavel();
        $responsavel->setNome($postVars['nome']);
        $responsavel->setEmail($postVars['email']);
        $responsavel->setTelefone($postVars['telefone']);
        $responsavel = $repository->save($responsavel);

        $request->getRouter()->redirect('/responsavel/editar/' . $id . '?status=edited');
    }

    /**
     * @param Request $request
     * @param ProfissionalRepository $repository
     * @return string
     */
    public static function removerResponsavelAction(Request $request, int $id, ResponsavelRepository $repository)
    {
        $return = $repository->remove($id);

        if($return === $id){
            $request->getRouter()->redirect('/responsavel?status=deleted');

        } else {
            $request->getRouter()->redirect('/responsavel?status=deletederror');
        }
    }
}