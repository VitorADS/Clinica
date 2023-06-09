<?php

namespace App\Models\Entitys;

use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity]
#[ORM\Table(schema: 'clinica', name: 'atendimento')]
class Atendimento{
    
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
    #[ORM\Column(name: 'observacoes', type:'string', nullable: false)]
    private $observacoes;

    /**
     * @var string
     */
    #[ORM\Column(name: 'descricao', type:'string', nullable: false)]
    private $descricao;

    /**
     * @var DateTime
     */
    #[ORM\Column(name: 'data', type:'datetime', nullable: false)]
    private $data;

    /**
     * @var Animal
     */
    #[ManyToOne(targetEntity: Animal::class)]
    #[JoinColumn(name: 'animal', referencedColumnName: 'id', nullable: false)]
    private $animal;

    /**
     * @var Clinica
     */
    #[ManyToOne(targetEntity: Clinica::class)]
    #[JoinColumn(name: 'clinica', referencedColumnName: 'id', nullable: false)]
    private $clinica;

    /**
     * @var ProfissionalClinica
     */
    #[ManyToOne(targetEntity: ProfissionalClinica::class)]
    #[JoinColumn(name: 'profissional_clinica', referencedColumnName: 'id', nullable: false)]
    private $profissionalClinica;

    /**
     * @var StatusAtendimento
     */
    #[ManyToOne(targetEntity: StatusAtendimento::class)]
    #[JoinColumn(name: 'status_atendimento', referencedColumnName: 'id', nullable: false)]
    private $statusAtendimento;

    /**
     * @var Pagamento
     */
    #[ManyToOne(targetEntity: Pagamento::class)]
    #[JoinColumn(name: 'pagamento', referencedColumnName: 'id', nullable: false)]
    private $pagamento;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getObservacoes(): string
    {
        return $this->observacoes;
    }

    /**
     * @param string $observacoes
     */
    public function setObservacoes(string $observacoes): self
    {
        $this->observacoes = $observacoes;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getData(): DateTime
    {
        return $this->data;
    }

    /**
     * @param DateTime $data
     */
    public function setData(DateTime $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Animal
     */
    public function getAnimal(): Animal
    {
        return $this->animal;
    }

    /**
     * @param Animal $animal
     */
    public function setAnimal(Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    /**
     * @return Clinica
     */
    public function getClinica(): Clinica
    {
        return $this->clinica;
    }

    /**
     * @param Clinica $clinica
     */
    public function setClinica(Clinica $clinica): self
    {
        $this->clinica = $clinica;

        return $this;
    }

    /**
     * @return ProfissionalClinica
     */
    public function getProfissionalClinica(): ProfissionalClinica
    {
        return $this->profissionalClinica;
    }

    /**
     * @param ProfissionalClinica profissionalClinica
     */
    public function setProfissionalClinica(ProfissionalClinica $profissionalClinica): self
    {
        $this->profissionalClinica = $profissionalClinica;

        return $this;
    }

    /**
     * @return StatusAtendimento
     */
    public function getStatusAtendimento(): StatusAtendimento
    {
        return $this->statusAtendimento;
    }

    /**
     * @param StatusAtendimento $statusAtendimento
     */
    public function setStatusAtendimento(StatusAtendimento $statusAtendimento): self
    {
        $this->statusAtendimento = $statusAtendimento;

        return $this;
    }

    /**
     * @return Pagamento
     */
    public function getPagamento(): Pagamento
    {
        return $this->pagamento;
    }

    /**
     * @param Pagamento $pagamento
     */
    public function setPagamento(Pagamento $pagamento): self
    {
        $this->pagamento = $pagamento;

        return $this;
    }
}