<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Animal;
use App\Models\Entitys\Responsavel;
use App\Models\Entitys\ResponsavelAnimal;
use App\Models\Repository\AnimalRepository;
use App\Models\Repository\ResponsavelAnimalRepository;
use App\Models\Repository\ResponsavelRepository;
use App\Utils\View;

class ResponsavelAnimalController extends PageController
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
            case 'responsaveladd':
                return AlertController::getSuccess('Responsavel adicionado com sucesso!');
                break;
            case 'responsavelremove':
                return AlertController::getSuccess('Responsavel removido com sucesso!');
                break;
            case 'padraoChanged':
                return AlertController::getSuccess('Responsavel padrao alterado');
                break;
            case 'responsavelRemoved':
                return AlertController::getSuccess('Responsavel Removido');
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * @param Request $request
     * @param AnimalRepository $repository
     * @param int $idAnimal
     * @return string
     */
    public static function getHome(Request $request, AnimalRepository $repository, int $idAnimal): string
    {
        $animal = $repository->find($idAnimal);

        if(!$animal instanceof Animal){
            $request->getRouter()->redirect('/animal?status=animalNotFound');
        }

        $content = '';
        $responsaveisAnimal = $animal->getResponsaveis();

        foreach($responsaveisAnimal as $responsavelAnimal){
            $content .= View::render('responsavelAnimal/responsavelItem', [
                'nome' => $responsavelAnimal->getResponsavel()->getNome(),
                'email' => $responsavelAnimal->getResponsavel()->getEmail(),
                'telefone' => $responsavelAnimal->getResponsavel()->getTelefone(),
                'padrao' => $responsavelAnimal->getPadrao() ? ComponentsController::createDisabledButton('primary', 'Definir Padrao') : ComponentsController::createButton('animal/responsavel/padrao?responsavel=' . $responsavelAnimal->getId(), 'primary', 'Definir Padrao', '', '', '', 'button'),
                'acao' => ComponentsController::createButton('animal/responsavel/remover?responsavel=' . $responsavelAnimal->getId(), 'danger', 'Remover', '', '', '', 'button')
            ]);
        }

        $content = View::render('responsavelAnimal/form', [
            'acao' => ComponentsController::createButton('animal/responsavel/adicionar/' . $animal->getId(), 'success', 'Adicionar'),
            'nome' => $animal->getNome(),
            'status' => self::getStatus($request),
            'method' => 'GET',
            'responsavel' => $content
        ]);

        return parent::getPage('Responsaveis de ' . $animal->getNome(), $content);
    }

    /**
     * @param Request $request
     * @param AnimalRepository $repository
     * @param int $idAnimal
     * @return string
     */
    public static function adicionarResponsavel(Request $request, AnimalRepository $animalRepository, int $idAnimal): string
    {
        $animal = $animalRepository->find($idAnimal);
        if(!$animal instanceof Animal){
            $request->getRouter()->redirect('/animal?status=animalNotFound');
        }

        $responsavelRepository = new ResponsavelRepository($animalRepository->getEm());
        $responsaveis = $responsavelRepository->getResponsaveisCadastraveis($animal);
        $content = '';

        foreach($responsaveis as $responsavel){
            $content .= View::render('responsavelAnimal/responsavelItem', [
                'nome' => $responsavel->getNome(),
                'email' => $responsavel->getEmail(),
                'telefone' => $responsavel->getTelefone(),
                'padrao' => '',
                'acao' => ComponentsController::createButton('animal/responsavel/adicionar/' . $animal->getId(), 'success', 'Adicionar', '', 'id_responsavel', $responsavel->getId(), 'submit')
            ]);
        }

        $content = View::render('responsavelAnimal/form', [
            'acao' => ComponentsController::createButton('animal/responsavel/listar/' . $animal->getId(), 'primary', 'Voltar'),
            'nome' => $animal->getNome(),
            'status' => self::getStatus($request),
            'method' => 'POST',
            'responsavel' => $content
        ]);

        return parent::getPage('Adicionar Responsavel de ' . $animal->getNome(), $content);
    }

    /**
     * @param Request $request
     * @param AnimalRepository $repository
     * @param int $idAnimal
     * @return string
     */
    public static function adicionarResponsavelAction(Request $request, AnimalRepository $animalRepository, int $idAnimal)
    {
        $animal = $animalRepository->find($idAnimal);

        if(!$animal instanceof Animal){
            $request->getRouter()->redirect('/animal?status=animalNotFound');
        }

        $postVars = $request->getPostVars();
        $responsavelRepository = new ResponsavelRepository($animalRepository->getEm());
        $responsavelAnimalRepository = new ResponsavelAnimalRepository($animalRepository->getEm());
        $responsavel = $responsavelRepository->find((int) $postVars['id_responsavel']);

        $responsavelAnimal = new ResponsavelAnimal();
        $responsavelAnimal->setAnimal($animal);
        $responsavelAnimal->setResponsavel($responsavel);
        $responsavelAnimal->setPadrao(false);
        $responsavelAnimal = $responsavelAnimalRepository->save($responsavelAnimal);

        if($responsavelAnimal instanceof ResponsavelAnimal && $responsavelAnimal->getId()){
            $request->getRouter()->redirect('/animal/responsavel/listar/' . $animal->getId() . '?status=responsaveladd');
        }
    }

    /**
     * @param Request $request
     * @param ResponsavelAnimalRepository $repository
     * @param int $idAnimal
     * @return void
     */
    public static function setPadrao(Request $request, ResponsavelAnimalRepository $repository)
    {
        $queryParams = $request->getQueryParams();
        $responsavelAnimal = $repository->find((int) $queryParams['responsavel']);
        
        if(!$responsavelAnimal instanceof ResponsavelAnimal){
            $request->getRouter()->redirect('/animal?status=responsavelNotFound');
        }

        $responsavelPadrao = $responsavelAnimal->getAnimal()->getResponsavelPadrao();

        if($responsavelPadrao instanceof ResponsavelAnimal){
            $responsavelPadrao->setPadrao(false);
            $repository->save($responsavelPadrao);
        }

        $responsavelAnimal->setPadrao(true);
        $repository->save($responsavelAnimal);
        $request->getRouter()->redirect('/animal/responsavel/listar/' . $responsavelAnimal->getAnimal()->getId() . '?status=padraoChanged');
    }

    /**
     * @param Request $request
     * @param ResponsavelAnimalRepository $repository
     * @param int $idAnimal
     * @return void
     */
    public static function removerResponsavelAnimal(Request $request, ResponsavelAnimalRepository $repository)
    {
        $queryParams = $request->getQueryParams();
        $responsavelAnimal = $repository->find((int) $queryParams['responsavel']);
        
        if(!$responsavelAnimal instanceof ResponsavelAnimal){
            $request->getRouter()->redirect('/animal?status=responsavelNotFound');
        }
        $idAnimal = $responsavelAnimal->getAnimal()->getId();

        $repository->remove($responsavelAnimal);
        $request->getRouter()->redirect('/animal/responsavel/listar/' . $idAnimal . '?status=responsavelRemoved');
    }
}