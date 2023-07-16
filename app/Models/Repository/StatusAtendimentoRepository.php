<?php

namespace App\Models\Repository;

use App\Models\Entitys\StatusAtendimento;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class StatusAtendimentoRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(StatusAtendimento::class, $em);
    }
}