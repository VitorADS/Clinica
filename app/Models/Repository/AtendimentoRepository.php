<?php

namespace App\Models\Repository;

use App\Models\Entitys\Atendimento;

class AtendimentoRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Atendimento::class);
    }
}