<?php

namespace App\Models\Repository;

use App\Models\Entitys\Atendimento;
use Doctrine\ORM\EntityManager;

class AtendimentoRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Atendimento::class, $em);
    }
}