<?php

namespace App\Models\Repository;

use App\Models\Entitys\Pagamento;
use App\Models\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class PagamentoRepository extends AbstractRepository
{
    public function __construct(?EntityManager $em = null)
    {
        parent::__construct(Pagamento::class, $em);
    }
}