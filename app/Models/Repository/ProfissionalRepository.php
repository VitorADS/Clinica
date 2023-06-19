<?php

namespace App\Models\Repository;

use App\Models\Entitys\Clinica;
use App\Models\Entitys\Profissional;
use App\Models\Entitys\ProfissionalClinica;

class ProfissionalRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Profissional::class);
    }

    public function getProfissionaisCadastraveis(Clinica $clinica)
    {
        $subConsulta = $this->getEntityManager()->createQueryBuilder();
        $subConsulta->select('pc');
        $subConsulta->from(ProfissionalClinica::class, 'pc');
        $subConsulta->where('pc.clinica = :clinica');
        $subConsulta->andWhere('pc.profissional = p.id');

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('p');
        $queryBuilder->from($this->getEntityName(), 'p');
        $queryBuilder->where($queryBuilder->expr()->not($queryBuilder->expr()->exists($subConsulta->getDQL())));
        $queryBuilder->setParameter('clinica', $clinica);

        return $queryBuilder->getQuery()->getResult();
    }
}