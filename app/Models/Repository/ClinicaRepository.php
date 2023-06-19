<?php

namespace App\Models\Repository;

use App\Models\Entitys\Clinica;

class ClinicaRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Clinica::class);
    }
}