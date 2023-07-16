<?php

namespace App\Models\Repository;

use App\Models\Entitys\Vacina;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class VacinaRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Vacina::class, $em);
    }
}