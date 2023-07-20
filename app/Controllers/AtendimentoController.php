<?php

namespace App\Controllers;

use App\Controllers\AlertController;
use App\Controllers\Pages\PageController;
use App\Http\Request;
use App\Models\Entitys\Atendimento;
use App\Models\Entitys\Clinica;
use App\Models\Repository\AnimalRepository;
use App\Models\Repository\AtendimentoRepository;
use App\Models\Repository\AtendimentoVacinaRepository;
use App\Models\Repository\ClinicaRepository;
use App\Models\Repository\PagamentoRepository;
use App\Models\Repository\ProfissionalClinicaRepository;
use App\Models\Repository\StatusAtendimentoRepository;
use App\Models\Repository\VacinaRepository;
use App\Utils\View;
use DateTime;

class AtendimentoController extends PageController
{

    private static function getStatus(Request $request): string
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'atendimentoNotFound':
                return AlertController::getError('Atendimento nao encontrado');
                break;
            case 'created':
                return AlertController::getSuccess('Atendimento criado');
                break;
            case 'updated':
                return AlertController::getSuccess('Atendimento editado');
                break;
            case 'appliedVaccine':
                return AlertController::getSuccess('Vacina aplicada');
                break;
            case 'removedVaccine':
                return AlertController::getSuccess('Aplicacao removida');
                break;
            case 'vaccineError':
                return AlertController::getError('Erro ao aplicar vacina');
                break;
            case 'vaccineNotFound':
                return AlertController::getError('Vacina nao encontrada');
                break;
            case 'aplicacaoNotFound':
                return AlertController::getError('Aplicacao nao encontrada');
                break;
            case 'aplicacaoRemoved':
                return AlertController::getSuccess('Aplicacao removida');
                break;
            case 'removed':
                return AlertController::getSuccess('Atendimento removido');
                break;
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
                'responsavelAnimal' => $atendimento->getAnimal()->getResponsavelPadrao() ? $atendimento->getAnimal()->getResponsavelPadrao()->getResponsavel()->getNome() : '',
                'statusAtendimento' => $atendimento->getStatusAtendimento()->getStatus(),
                'editar' => ComponentsController::createButton('atendimento/editar/' . $atendimento->getId(), 'primary', 'Editar'),
                'excluir' => ComponentsController::createButton('atendimento/remover/' . $atendimento->getId(), 'danger', 'Remover')
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
     * @param AnimalRepository $animalRepository
     * @return string
     */
    public static function criarAtendimento(Request $request, AnimalRepository $animalRepository): string
    {
        $animal = $request->getQueryParams()['animal'] ? $animalRepository->find((int) $request->getQueryParams()['animal']) : null;

        if(!$animal) $request->getRouter()->redirect('/animal?status=animalNotFound');

        $clinicasText = ComponentsController::createOptionSelect('', 'Selecione a clinica');
        $statusText = '';
        $pagamentosText = '';

        $clinicaRepository = new ClinicaRepository($animalRepository->getEm());
        $statusRespository = new StatusAtendimentoRepository($animalRepository->getEm());
        $pagamentoRepository = new PagamentoRepository($animalRepository->getEm());

        $clinicas = $clinicaRepository->findAll();
        $status = $statusRespository->findAll();
        $pagamentos = $pagamentoRepository->findAll();

        foreach($clinicas as $clinica){
            $clinicasText .= ComponentsController::createOptionSelect($clinica->getId(), $clinica->getNome());
        }

        foreach($status as $stats){
            $statusText .= ComponentsController::createOptionSelect($stats->getId(), $stats->getStatus());
        }

        foreach($pagamentos as $pagamento){
            $pagamentosText .= ComponentsController::createOptionSelect($pagamento->getId(), $pagamento->getPagamento());
        }

        $content = View::render('atendimento/formAtendimento', [
            'clinicas' => $clinicasText,
            'animal' => ComponentsController::createOptionSelect($animal->getId(), $animal->getNome()),
            'statusAtendimento' => $statusText,
            'pagamento' => $pagamentosText,
            'descricao' => '',
            'observacao' => '',
            'dataAtendimento' => '',
            'vacinasAtendimento' => '',
            'modal' => '',
            'action' => 'Criar',
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Criar Atendimento', $content);
    }

    /**
     * @param Request $request
     * @param AtendimentoRepository $atendimentoRepository
     * @return void
     */
    public static function criarAtendimentoAction(Request $request, AtendimentoRepository $atendimentoRepository): void
    {
        self::saveAtendimento($request, $atendimentoRepository);
    }

    /**
     * @param Request $request
     * @param AtendimentoRepository $atendimentoRepository
     * @param int $id
     * @return string
     */
    public static function editarAtendimento(Request $request, AtendimentoRepository $atendimentoRepository, int $id): string
    {
        $atendimento = $atendimentoRepository->find($id);

        if(!$atendimento instanceof Atendimento){
            $request->getRouter()->redirect('/atendimento?status=atendimentoNotFound');
        }

        $status = (new StatusAtendimentoRepository($atendimentoRepository->getEm()))->findAll();
        $pagamentos = (new PagamentoRepository($atendimentoRepository->getEm()))->findAll();
        $atendimentoVacinas = (new AtendimentoVacinaRepository($atendimentoRepository->getEm()))->findBy(['atendimento' => $atendimento]);
        $vacinas = (new VacinaRepository($atendimentoRepository->getEm()))->findAll();
        $vacinasList = '';
        $statusText = '';
        $pagamentosText = '';
        $vacinasItens = '';

        foreach($status as $stats){
            $selected = $stats->getId() === $atendimento->getStatusAtendimento()->getId() ? true : false;
            $statusText .= ComponentsController::createOptionSelect($stats->getId(), $stats->getStatus(), $selected);
        }

        foreach($pagamentos as $pagamento){
            $selected = $pagamento->getId() === $atendimento->getPagamento()->getId() ? true : false;
            $pagamentosText .= ComponentsController::createOptionSelect($pagamento->getId(), $pagamento->getPagamento(), $selected);
        }

        foreach($atendimentoVacinas as $atendimentoVacina){
            $vacinasItens .= View::render('atendimento/partials/item', [
                'nome' => $atendimentoVacina->getVacina()->getNome(),
                'dataAplicacao' => $atendimentoVacina->getCreatedAt()->format('d/m/Y'),
                'remover' => ComponentsController::createButton('atendimentovacina/remover/' . $atendimentoVacina->getId(), 'danger', 'Remover Aplicacao')
            ]);
        }

        foreach($vacinas as $vacina){
            $vacinasList .= ComponentsController::createOptionSelect($vacina->getId(), $vacina->getNome());
        }

        $vacinasAplicadas = View::render('atendimento/partials/vacinas', [
            'adicionarVacina' => ComponentsController::createButtonOptions('data-bs-toggle="modal" data-bs-target="#modalVacina"', 'Adicionar Aplicacao', 'success'),
            'vacinas' => $vacinasItens
        ]);

        $vacinasAtendimento = View::render('atendimento/partials/vacinasAtendimento', [
            'vacinas' => $vacinasAplicadas
        ]);

        $modal = View::render('atendimento/partials/criarAplicacao', [
            'vacinas' => $vacinasList,
            'idAtendimento' => $atendimento->getId()
        ]);

        $content = View::render('atendimento/formAtendimento', [
            'clinicas' => ComponentsController::createOptionSelect($atendimento->getClinica()->getId(), $atendimento->getClinica()->getNome()),
            'animal' => ComponentsController::createOptionSelect($atendimento->getAnimal()->getId(), $atendimento->getAnimal()->getNome()),
            'dataAtendimento' => $atendimento->getData()->format('Y-m-d'),
            'statusAtendimento' => $statusText,
            'pagamento' => $pagamentosText,
            'descricao' => $atendimento->getDescricao(),
            'observacao' => $atendimento->getObservacoes(),
            'vacinasAtendimento' => $vacinasAtendimento,
            'modal' => $modal,
            'action' => 'Editar',
            'status' => self::getStatus($request)
        ]);

        return parent::getPage('Editar Atendimento', $content);
    }

    /**
     * @param Request $request
     * @param AtendimentoRepository $atendimentoRepository
     * @param int $id
     * @return string
     */
    public static function editarAtendimentoAction(Request $request, AtendimentoRepository $atendimentoRepository, int $id)
    {
        self::saveAtendimento($request, $atendimentoRepository, $id);
    }

    /**
     * @param Request $request
     * @param AtendimentoRepository $atendimentoRepository
     * @param int $id
     * @return void
     */
    private static function saveAtendimento(Request $request, AtendimentoRepository $atendimentoRepository, ?int $id = null): void
    {
        if(!$id){
            $atendimento = new Atendimento();
            $statusAtendimento = 'created';

        } else {
            $atendimento = $atendimentoRepository->find($id);
            $statusAtendimento = 'updated';

            if(!$atendimento instanceof Atendimento){
                $request->getRouter()->redirect('/atendimento?status=atendimentoNotFound');
            }
        }

        $postVars = $request->getPostVars();
        $clinicaRepository = new ClinicaRepository($atendimentoRepository->getEm());
        $animalRepository = new AnimalRepository($atendimentoRepository->getEm());
        $profissionalClinicaRepository = new ProfissionalClinicaRepository($atendimentoRepository->getEm());
        $statusRepository = new StatusAtendimentoRepository($atendimentoRepository->getEm());
        $pagamentoRepository = new PagamentoRepository($atendimentoRepository->getEm());

        $clinica = $clinicaRepository->find((int) $postVars['clinica']);
        $animal = $animalRepository->find((int) $postVars['animal']);
        $profissional = $profissionalClinicaRepository->find((int) $postVars['profissional']);
        $status = $statusRepository->find((int) $postVars['status_atendimento']);
        $pagamento = $pagamentoRepository->find((int) $postVars['pagamento']);

        $atendimento->setAnimal($animal);
        $atendimento->setClinica($clinica);
        $atendimento->setProfissionalClinica($profissional);
        $atendimento->setStatusAtendimento($status);
        $atendimento->setPagamento($pagamento);
        $atendimento->setDescricao($postVars['descricao']);
        $atendimento->setObservacoes($postVars['observacao']);
        $atendimento->setData(new DateTime($postVars['date']));
        $atendimento = $atendimentoRepository->save($atendimento);

        $request->getRouter()->redirect('/atendimento/editar/' . $atendimento->getId() . '?status=' . $statusAtendimento);
    }

    /**
     * @param Request $request
     * @param AtendimentoRepository $atendimentoRepository
     * @param int $id
     * @return void
     */
    public static function removerAtendimentoAction($request, AtendimentoRepository $atendimentoRepository, $id)
    {
        $atendimentoRepository->remove($id);

        $request->getRouter()->redirect('/atendimento?status=removed');
    }
}