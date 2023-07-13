<?php

namespace App\Models\Entitys;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

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
     * @var float
     */
    #[ORM\Column(name: 'altura', type:'float', nullable: false)]
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
    #[JoinColumn(name: 'raca', referencedColumnName: 'id', nullable: true)]
    private $raca;

    /**
     * @var ArrayCollection
     */
    #[OneToMany(targetEntity: ResponsavelAnimal::class, mappedBy: 'animal')]
    private $responsaveis;

    /**
     * @var ArrayCollection
     */
    #[OneToMany(targetEntity: Atendimento::class, mappedBy: 'animal')]
    private $atendimentos;

    public function __construct()
    {
        $this->responsaveis = new ArrayCollection();
        $this->atendimentos = new ArrayCollection();
    }

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
     * @return float
     */
    public function getAltura(): float
    {
        return $this->altura;
    }

    /**
     * @param float $altura
     */
    public function setAltura(float $altura)
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
     * @param DateTime $dataNascimento
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
    public function setTipo(Tipo $tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return Raca
     */
    public function getRaca(): ?Raca
    {
        return $this->raca;
    }

    /**
     * @param Raca $raca
     */
    public function setRaca(?Raca $raca = null)
    {
        $this->raca = $raca;
    }

    public function getIdade(): string
    {
        return $this->getDataNascimento()->diff(new DateTime())->format('%y anos, %m meses e %d dias');
    }

    public function getResponsaveis()
    {
        return $this->responsaveis;
    }

    public function getAtendimentos()
    {
        return $this->atendimentos;
    }

    public function getResponsavelPadrao(): ?ResponsavelAnimal
    {
        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('padrao', true));
        $result = $this->getResponsaveis()->matching($criteria);

        return $result->count() === 1 ? $result->first() : null;
    }
}