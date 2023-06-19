<?php

namespace App\Models\Repository;

use App\Models\Entitys\ProfissionalClinica;

class ProfissionalClinicaRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(ProfissionalClinica::class);
    }
}