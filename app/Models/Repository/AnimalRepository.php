<?php

namespace App\Models\Repository;

use App\Models\Entitys\Animal;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class AnimalRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Animal::class, $em);
    }
}