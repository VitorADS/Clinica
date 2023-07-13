<?php

namespace App\Models\Repository;

use App\Models\Entitys\Raca;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class RacaRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Raca::class, $em);
    }
}