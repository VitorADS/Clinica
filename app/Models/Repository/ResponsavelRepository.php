<?php

namespace App\Models\Repository;

use App\Models\Entitys\Animal;
use App\Models\Entitys\Responsavel;
use App\Models\Entitys\ResponsavelAnimal;
use Doctrine\ORM\EntityManager;

class ResponsavelRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Responsavel::class, $em);
    }

    public function getResponsaveisCadastraveis(Animal $animal)
    {
        $subConsulta = $this->getEntityManager()->createQueryBuilder();
        $subConsulta->select('ra');
        $subConsulta->from(ResponsavelAnimal::class, 'ra');
        $subConsulta->where('ra.animal = :animal');
        $subConsulta->andWhere('ra.responsavel = r.id');

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('r');
        $queryBuilder->from($this->getEntityName(), 'r');
        $queryBuilder->where($queryBuilder->expr()->not($queryBuilder->expr()->exists($subConsulta->getDQL())));
        $queryBuilder->setParameter('animal', $animal);

        return $queryBuilder->getQuery()->getResult();
    }
}