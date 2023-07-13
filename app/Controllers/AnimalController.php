<?php

namespace App\Controllers;

use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Animal;
use App\Models\Entitys\Raca;
use App\Models\Entitys\Tipo;
use App\Models\Repository\AnimalRepository;
use App\Models\Repository\RacaRepository;
use App\Models\Repository\TipoRepository;
use App\Utils\View;
use DateTime;

class AnimalController extends PageController
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
                return AlertController::getSuccess('Animal criado!');
                break;  
            case 'emptyEdit':
                return AlertController::getError('Animal nao encontrado!');
                break;   
            case 'edited':
                return AlertController::getSuccess('Animal editado!');
                break;      
            case 'deleted':
                return AlertController::getSuccess('Animal removido!');
                break; 
            case 'animalService':
                return AlertController::getError('Animal possui atendimento vinculado!');
                break;
            case 'deletederror':
                return AlertController::getSuccess('Erro ao remover animal!');
                break;      
            case 'animalNotFound':
                return AlertController::getError('Animal nao encontrado!');
                break;  
            case 'notFoundAtend':
                return AlertController::getError('Animal nao encontrado ou possui atendimento vinculado');
                break; 
            case 'responsavelNotFound':
                return AlertController::getError('Responsavel nao encontrado!');
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * @param Request $request
     * @param AnimalRepository $repository
     * @return string
     */
    public static function getHome(Request $request, AnimalRepository $repository): string
    {
        $animais = $repository->findBy([], ['nome' => 'ASC']);
        $content = '';

        foreach($animais as $animal){
            $content .= View::render('animal/item', [
                'id' => $animal->getId(),
                'nome' => $animal->getNome(),
                'tipo' => $animal->getTipo()->getTipo(),
                'raca' => $animal->getRaca()->getRaca(),
                'cor' => $animal->getCor(),
                'peso' => $animal->getPeso() . ' Kg',
                'altura' => $animal->getAltura() . ' Cm',
                'idade' => $animal->getIdade(),
                'responsavel' => $animal->getResponsavelPadrao() ? $animal->getResponsavelPadrao()->getResponsavel()->getNome() : ''  
            ]);
        }

        $content = View::render('animal/home', [
            'status' => self::getStatus($request),
            'animais' => $content
        ]);

        return parent::getPage('Animais', $content);
    }

    /**
     * @param Request $request
     * @param TipoRepository $tipoRepository
     * @param RacaRepository $racaRepository
     * @return string
     */
    public static function criarAnimal(Request $request, TipoRepository $tipoRepository, RacaRepository $racaRepository): string
    {
        $optionsTipo = '';
        $optionsRaca = '';
        $tipos = $tipoRepository->findAll();
        $racas = $racaRepository->findAll();
        
        foreach($tipos as $tipo){
            $optionsTipo .= View::render('animal/option', [
                'selected' => '',
                'id' => $tipo->getId(),
                'option' => $tipo->getTipo()
            ]);
        }

        foreach($racas as $raca){
            $optionsRaca .= View::render('animal/option', [
                'selected' => '',
                'id' => $raca->getId(),
                'option' => $raca->getRaca()
            ]);
        }

        $content = View::render('animal/form', [
            'action' => 'Cadastrar',
            'nome' => '',
            'tipo' => $optionsTipo,
            'raca' => $optionsRaca,
            'cor' => '',
            'peso' => '',
            'altura' => '',
            'idadedata' => 'Data de Nascimento:',
            'idade' => '',
            'datetype' => 'date',
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Cadastrar Animal', $content);
    }

    /**
     * @param Request $request
     * @param AnimalRepository $repository
     * @return string
     */
    public static function criarAnimalAction(Request $request, AnimalRepository $repository)
    {
        $postVars = $request->getPostVars();

        $animal = self::saveAnimal($request, $postVars, $repository);

        if($animal instanceof Animal && is_numeric($animal->getId())){
            $request->getRouter()->redirect('/animal/editar/' . $animal->getId() . '?status=created');

        } else {
            $request->getRouter()->redirect('/animal/criar?status=error');
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @param AnimalRepository $animalRepository
     * @param TipoRepository $tipoRepository
     * @param RacaRepository $racaRepository
     * @return string
     */
    public static function editarAnimal(Request $request, int $id, AnimalRepository $animalRepository, TipoRepository $tipoRepository, RacaRepository $racaRepository): string
    {
        $animal = $animalRepository->find($id);

        if(!$animal instanceof Animal){
            $request->getRouter()->redirect('/animal?status=animalNotFound');
        }

        $optionsTipo = '';
        $optionsRaca = '';
        $tipos = $tipoRepository->findAll();
        $racas = $racaRepository->findAll();
        
        foreach($tipos as $tipo){
            $optionsTipo .= View::render('animal/option', [
                'selected' => $animal->getTipo()->getId() === $tipo->getId() ? 'selected' : '',
                'id' => $tipo->getId(),
                'option' => $tipo->getTipo()
            ]);
        }

        foreach($racas as $raca){
            $optionsRaca .= View::render('animal/option', [
                'selected' => $animal->getRaca()->getId() === $raca->getId() ? 'selected' : '',
                'id' => $raca->getId(),
                'option' => $raca->getRaca()
            ]);
        }

        $content = View::render('animal/form', [
            'action' => 'Editar',
            'nome' => $animal->getNome(),
            'tipo' => $optionsTipo,
            'raca' => $optionsRaca,
            'cor' => $animal->getCor(),
            'peso' => $animal->getPeso(),
            'altura' => $animal->getAltura(),
            'idadedata' => 'Data de Nascimento:',
            'idade' => $animal->getDataNascimento()->format('Y-m-d'),
            'datetype' => 'date',
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Editar Animal', $content);
    }

    /**
     * @param Request $request
     * @param AnimalRepository $repository
     * @param TipoRepository $tipoRepository
     * @param RacaRepository $racaRepository
     * @return string
     */
    public static function editarAnimalAction(Request $request, int $id, AnimalRepository $animalRepository)
    {
        $postVars = $request->getPostVars();
        $animal = $animalRepository->find($id);

        if(!$animal instanceof Animal){
            $request->getRouter()->redirect('/animal?status=animalNotFound');
        }

        $animal = self::saveAnimal($request, $postVars, $animalRepository, $animal);

        if($animal instanceof Animal && is_numeric($animal->getId())){
            $request->getRouter()->redirect('/animal/editar/' . $animal->getId() . '?status=edited');

        } else {
            $request->getRouter()->redirect('/animal/criar?status=error');
        }
    }

    /**
     * @param Request $request
     * @param array $postVars
     * @param AnimalRepository $animalRepository
     * @param ?Animal $animal = null
     * @return Animal
     */
    private static function saveAnimal(Request $request, array $postVars, AnimalRepository $animalRepository, ?Animal $animal = null): Animal
    {
        if(!$animal instanceof Animal){
            $animal = new Animal();
        }

        $tipoRepository = new TipoRepository($animalRepository->getEm());
        $racaRepository = new RacaRepository($animalRepository->getEm());

        $tipo = $tipoRepository->find($postVars['tipo']);
        $raca = $racaRepository->find($postVars['raca']);

        $animal->setNome($postVars['nome']);
        $animal->setTipo($tipo);
        $animal->setRaca($raca);
        $animal->setCor($postVars['cor']);
        $animal->setPeso($postVars['peso']);
        $animal->setAltura($postVars['altura']);
        $animal->setDataNascimento(new DateTime($postVars['data_nascimento']));

        return $animalRepository->save($animal);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param AnimalRepository $animalRepository
     * @return void
     */
    public static function removeAnimal(Request $request, int $id, AnimalRepository $animalRepository): void
    {
        $animal = $animalRepository->find($id);

        if($animal instanceof Animal && $animal->getAtendimentos()->count() === 0){
            $id = $animalRepository->remove($animal);

            if(is_numeric($id)){
                $request->getRouter()->redirect('/animal?status=deleted');
            }

        } else {
            $request->getRouter()->redirect('/animal/?status=notFoundAtend');
        }
    }

    public static function getResponsaveis(Request $request, AnimalRepository $animalRepository, int $id): string
    {
        $animal = $animalRepository->find($id);

        if(!$animal instanceof Animal){
            $request->getRouter()->redirect('/animal?status=animalNotFound');
        }

        $content = '';
        $responsaveis = $animal->getResponsaveis();

        foreach($responsaveis as $responsavel){
            $content .= View::render('animal/responsavelItem', [
                'nome' => $responsavel->getNome(),
                'email' => $responsavel->getEmail(),
                'telefone' => $responsavel->getTelefone(),
                'id' => $responsavel->getId(),
                'status' => self::getStatus($request)
            ]);
        }

        $content = View::render('animal/responsavel', [
            'status' => self::getStatus($request),
            'nome' => $animal->getNome(),
            'responsavel' => $content
        ]);

        return parent::getPage('Responsaveis de ' . $animal->getNome(), $content);
    }
}