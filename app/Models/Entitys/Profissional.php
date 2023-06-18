<?php

namespace App\Models\Entitys;

use App\Models\Repository\ProfissionalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(schema: 'clinica', name: 'profissional')]
#[ORM\Entity(ProfissionalRepository::class)]
class Profissional{
    
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
    #[ORM\Column(name: 'telefone', type:'string', nullable: false)]
    private $telefone;

    /**
     * @var string
     */
    #[ORM\Column(name: 'email', type:'string', nullable: false)]
    private $email;

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
    public function getTelefone(): string
    {
        return $this->telefone;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone(string $telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
}