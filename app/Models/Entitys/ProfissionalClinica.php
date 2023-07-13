<?php

namespace App\Models\Entitys;

use App\Models\Repository\ProfissionalClinicaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Table(schema: 'clinica', name: 'profissional_clinica')]
#[ORM\Entity(ProfissionalClinicaRepository::class)]
class ProfissionalClinica{
    
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var Clinica
     */
    #[ManyToOne(targetEntity: Clinica::class, inversedBy: 'profissionaisClinica')]
    #[JoinColumn(name: 'clinica', referencedColumnName: 'id', nullable: false)]
    private $clinica;

    /**
     * @var Profissional
     */
    #[ManyToOne(targetEntity: Profissional::class, inversedBy: 'profissionaisClinica')]
    #[JoinColumn(name: 'profissional', referencedColumnName: 'id', nullable: false)]
    private $profissional;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Clinica
     */
    public function getClinica(): Clinica
    {
        return $this->clinica;
    }

    /**
     * @param string $clinica
     */
    public function setClinica(Clinica $clinica)
    {
        $this->clinica = $clinica;
    }

    /**
     * @return Profissional
     */
    public function getProfissional(): Profissional
    {
        return $this->profissional;
    }

    /**
     * @param Profissional $profissional
     */
    public function setProfissional(Profissional $profissional)
    {
        $this->profissional = $profissional;
    }
}