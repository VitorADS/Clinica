<?php

namespace App\Models\Entitys;

use App\Models\Repository\PagamentoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(schema: 'clinica', name: 'pagamento')]
#[ORM\Entity(PagamentoRepository::class)]
class Pagamento{
    
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
    #[ORM\Column(name: 'pagamento', type:'string', nullable: false)]
    private $pagamento;

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
    public function getPagamento(): string
    {
        return $this->pagamento;
    }

    /**
     * @param string $pagamento
     */
    public function setPagamento(string $pagamento)
    {
        $this->pagamento = $pagamento;
    }
}