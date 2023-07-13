<?php

namespace App\Models\Repository;

use App\Models\Entitys\Clinica;
use Doctrine\ORM\EntityManager;

class ClinicaRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Clinica::class, $em);
    }
}