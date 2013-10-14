<?php

namespace Endereco\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Cidade {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    * @ORM\Column(type="integer")
    */
    protected $cidcodigo = null;
    /** @ORM\Column(type="string") */
    protected $nome;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="cidades")
     * @ORM\JoinColumn(name="sigla", referencedColumnName="sigla")
     */
    protected $estado;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Bairro", mappedBy="cidade")
     */
    protected $bairros;
    
    public function exchangeArray($data) {
        $this->cidcodigo = (isset($data['cidcodigo'])) ? $data['cidcodigo'] : null;
        $this->nome      = (isset($data['nome'])) ? $data['nome'] : null;
        $this->sigla = (isset($data['sigla'])) ? $data['sigla'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function getCidcodigo() {
        return $this->cidcodigo;
    }

    public function setCidcodigo($cidcodigo) {
        $this->cidcodigo = $cidcodigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getBairros() {
        return $this->bairros;
    }

    public function setBairros($bairros) {
        $this->bairros = $bairros;
    }


}