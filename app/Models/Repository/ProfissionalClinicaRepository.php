<?php

namespace App\Models\Repository;

use App\Models\Entitys\ProfissionalClinica;
use Doctrine\ORM\EntityManager;

class ProfissionalClinicaRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(ProfissionalClinica::class, $em);
    }
}