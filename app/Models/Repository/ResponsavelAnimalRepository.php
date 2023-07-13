<?php

namespace App\Models\Repository;

use App\Models\Entitys\ResponsavelAnimal;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class ResponsavelAnimalRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(ResponsavelAnimal::class, $em);
    }
}