<?php

namespace App\Models\Repository;

use App\Models\Entitys\Atendimento;
use App\Models\Entitys\AtendimentoVacina;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class AtendimentoVacinaRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(AtendimentoVacina::class, $em);
    }
}