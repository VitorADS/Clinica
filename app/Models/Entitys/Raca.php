<?php

namespace App\Models\Entitys;

use App\Models\Repository\RacaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(schema: 'clinica', name: 'raca')]
#[ORM\Entity(RacaRepository::class)]
class Raca{
    
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'raca', type:'string', nullable: false)]
    private $raca;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRaca(): string
    {
        return $this->raca;
    }

    /**
     * @param string $raca
     */
    public function setRaca(string $raca)
    {
        $this->raca = $raca;
    }
}