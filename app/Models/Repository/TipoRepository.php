<?php

namespace App\Models\Repository;

use App\Models\Entitys\Tipo;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class TipoRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Tipo::class, $em);
    }
}