<?php

namespace App\Models\Entitys;

use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity]
#[ORM\Table(schema: 'clinica', name: 'animal')]
class Animal{
    
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
    #[ORM\Column(name: 'nome', type:'string', nullable: false)]
    private $nome;

    /**
     * @var string
     */
    #[ORM\Column(name: 'cor', type:'string', nullable: false)]
    private $cor;

    /**
     * @var float
     */
    #[ORM\Column(name: 'peso', type:'float', nullable: false)]
    private $peso;

    /**
     * @var string
     */
    #[ORM\Column(name: 'altura', type:'string', nullable: false)]
    private $altura;

    /**
     * @var DateTime
     */
    #[ORM\Column(name: 'data_nascimento', type:'date', nullable: false)]
    private $dataNascimento;

    /**
     * @var Tipo
     */
    #[ManyToOne(targetEntity: Tipo::class)]
    #[JoinColumn(name: 'tipo', referencedColumnName: 'id', nullable: false)]
    private $tipo;

    /**
     * @var Raca
     */
    #[ManyToOne(targetEntity: Raca::class)]
    #[JoinColumn(name: 'raca', referencedColumnName: 'id', nullable: false)]
    private $raca;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getCor(): string
    {
        return $this->cor;
    }

    /**
     * @param string $cor
     */
    public function setCor(string $cor)
    {
        $this->cor = $cor;
    }

    /**
     * @return float
     */
    public function getPeso(): string
    {
        return $this->peso;
    }

    /**
     * @param float $peso
     */
    public function setPeso(float $peso)
    {
        $this->peso = $peso;
    }

    /**
     * @return string
     */
    public function getAltura(): string
    {
        return $this->altura;
    }

    /**
     * @param string $altura
     */
    public function setAltura(string $altura)
    {
        $this->altura = $altura;
    }

    /**
     * @return DateTime
     */
    public function getDataNascimento(): DateTime
    {
        return $this->dataNascimento;
    }

    /**
     * @param DateTime $altura
     */
    public function setDataNascimento(DateTime $dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }

    /**
     * @return Tipo
     */
    public function getTipo(): Tipo
    {
        return $this->tipo;
    }

    /**
     * @param Tipo $tipo
     */
    public function setTipo(string $tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return Raca
     */
    public function getRaca(): Raca
    {
        return $this->raca;
    }

    /**
     * @param Raca $tipo
     */
    public function setRaca(Raca $raca)
    {
        $this->raca = $raca;
    }

    public function getIdade(): DateInterval
    {
        return $this->getDataNascimento()->diff(new DateTime());
    }
}