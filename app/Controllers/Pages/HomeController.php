<?php

namespace App\Controllers\Pages;

use App\Controllers\ComponentsController;
use App\Models\Entitys\Atendimento;
use App\Models\Repository\AtendimentoRepository;
use App\Utils\View;

class HomeController extends PageController{

    /**
     * @return string
     */
    public static function getHome() : string
    {
        $atendimentos = (new AtendimentoRepository())->findBy([], null, 5);
        $textAtendimentos = '';

        /** @var Atendimento */
        foreach($atendimentos as $atendimento){
            $textAtendimentos .= View::render('home/item', [
                'clinica' => $atendimento->getClinica()->getNome(),
                'profissional' => $atendimento->getProfissionalClinica()->getProfissional()->getNome(),
                'animal' => $atendimento->getAnimal()->getNome(),
                'responsavelAnimal' => $atendimento->getAnimal()->getResponsavelPadrao() ? $atendimento->getAnimal()->getResponsavelPadrao()->getResponsavel()->getNome() : '',
                'linkAtendimento' => ComponentsController::createButton('atendimento/editar/' . $atendimento->getId(), 'primary', 'Acessar Atendimento')
            ]);
        }

        $table = View::render('home/table', [
            'atendimentos' => $textAtendimentos
        ]);

        $content = View::render('home/home', [
            'table' => $table
        ]);

        return parent::getPage('Home', $content);
    }
}

?>