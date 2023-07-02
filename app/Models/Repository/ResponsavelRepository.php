<?php

namespace App\Models\Repository;

use App\Models\Entitys\Responsavel;

class ResponsavelRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Responsavel::class);
    }
}